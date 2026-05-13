<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenebusanRequest;
use App\Models\Penebusan;
use App\Models\ScheduleAgreement;
use App\Models\StockHistory;
use App\Models\StockSummary;
use App\Models\Truck;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenebusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penebusan::with(['scheduleAgreement', 'truck', 'driver']);

        // Filter by driver if user is supir_knek
        if (auth()->user()->hasRole('supir_knek')) {
            $driver = auth()->user()->driver;
            if ($driver) {
                $query->where('driver_id', $driver->id);
            } else {
                // If user is supir but has no driver profile, they shouldn't see anything or handle it gracefully
                $query->where('id', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where('nomor_do', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_penebusan', $request->tanggal);
        }

        $penebusans = $query->latest()->paginate(10);

        return view('penebusan.index', compact('penebusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $isDriver = $user->hasRole('supir_knek');
        $driverProfile = $user->driver;

        $sasQuery = ScheduleAgreement::where('status_sa', 'pending');
        
        if ($isDriver) {
            if ($driverProfile) {
                $sasQuery->where('driver_id', $driverProfile->id);
            } else {
                return redirect()->route('penebusan.index')->with('error', 'Profil driver tidak ditemukan untuk akun Anda.');
            }
        }

        $sas = $sasQuery->get();
        $drivers = Driver::where('status', 'aktif')->whereNotNull('truck_id')->get();
        
        $selectedSaId = $request->sa_id;
        $selectedSa = null;
        if ($selectedSaId) {
            $selectedSa = ScheduleAgreement::find($selectedSaId);
            // Verify if this SA belongs to the driver if user is driver
            if ($isDriver && $selectedSa && $selectedSa->driver_id != $driverProfile->id) {
                $selectedSa = null;
            }
        }

        return view('penebusan.create', compact('drivers', 'sas', 'selectedSa', 'isDriver', 'driverProfile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenebusanRequest $request)
    {
        $data = $request->validated();
        
        $sa = ScheduleAgreement::findOrFail($data['schedule_agreement_id']);
        
        // If user is driver, ensure they own this SA
        if (auth()->user()->hasRole('supir_knek')) {
            $driverProfile = auth()->user()->driver;
            if (!$driverProfile || $sa->driver_id != $driverProfile->id) {
                return back()->with('error', 'Anda tidak memiliki otoritas untuk SA ini.');
            }
            $data['driver_id'] = $driverProfile->id;
        } else {
            // Admin flow, use driver_id from request or SA if not provided
            $data['driver_id'] = $data['driver_id'] ?? $sa->driver_id;
        }

        // Use truck_id from SA if available, otherwise from driver profile
        if ($sa->truck_id) {
            $data['truck_id'] = $sa->truck_id;
        } else {
            $driver = Driver::findOrFail($data['driver_id']);
            if (!$driver->truck_id) {
                return back()->with('error', 'Driver/SA tidak memiliki Truk yang terasosiasi.')->withInput();
            }
            $data['truck_id'] = $driver->truck_id;
        }

        // 1 DO = 560 tabung
        $data['jumlah_tabung'] = 560; 
        $data['harga_per_do'] = 6487298;
        $data['total_penebusan'] = $data['harga_per_do'];
        $data['status_penebusan'] = 'berhasil';

        DB::beginTransaction();

        try {
            if ($request->hasFile('foto_nota')) {
                $data['foto_nota'] = $request->file('foto_nota')->store('penebusan', 'public');
            }

            $penebusan = Penebusan::create($data);

            // Update Vehicle Stock
            $vStock = \App\Models\VehicleStock::firstOrCreate(
                ['truck_id' => $penebusan->truck_id],
                ['stok_saat_ini' => 0]
            );
            $oldVStock = $vStock->stok_saat_ini;
            $newVStock = $oldVStock + $penebusan->jumlah_tabung;
            $vStock->update(['stok_saat_ini' => $newVStock]);

            // Create Vehicle History
            \App\Models\VehicleStockHistory::create([
                'tanggal' => $penebusan->tanggal_penebusan,
                'truck_id' => $penebusan->truck_id,
                'jenis_mutasi' => 'penebusan',
                'referensi' => $penebusan->nomor_do,
                'stok_masuk' => $penebusan->jumlah_tabung,
                'stok_keluar' => 0,
                'stok_akhir' => $newVStock,
                'keterangan' => 'Penebusan DO #' . $penebusan->nomor_do,
            ]);

            // Check SA status
            $sa = $penebusan->scheduleAgreement;
            $totalTabungInSa = $sa->penebusans()->sum('jumlah_tabung');
            if ($totalTabungInSa >= $sa->jumlah_tabung) {
                $sa->update(['status_sa' => 'selesai']);
            }

            DB::commit();

            return redirect()->route('penebusan.index')
                ->with('success', 'Penebusan DO berhasil dicatat dan stok gudang bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($data['foto_nota'])) {
                Storage::disk('public')->delete($data['foto_nota']);
            }
            return back()->with('error', 'Gagal mencatat penebusan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Penebusan $penebusan)
    {
        $penebusan->load(['scheduleAgreement', 'truck', 'driver']);
        return view('penebusan.show', compact('penebusan'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penebusan $penebusan)
    {
        // This is tricky because it affects stock.
        // Prompt doesn't explicitly mention delete, but usually you shouldn't delete financial records.
        // I'll disable it or handle with caution.
        
        DB::beginTransaction();
        try {
            $vStock = \App\Models\VehicleStock::where('truck_id', $penebusan->truck_id)->first();
            if (!$vStock || $vStock->stok_saat_ini < $penebusan->jumlah_tabung) {
                throw new \Exception('Stok kendaraan tidak cukup untuk membatalkan penebusan ini.');
            }

            $newVStock = $vStock->stok_saat_ini - $penebusan->jumlah_tabung;
            $vStock->update(['stok_saat_ini' => $newVStock]);

            // Record history for deletion
            \App\Models\VehicleStockHistory::create([
                'tanggal' => now(),
                'truck_id' => $penebusan->truck_id,
                'jenis_mutasi' => 'penyesuaian_stok',
                'referensi' => 'BATAL-' . $penebusan->nomor_do,
                'stok_masuk' => 0,
                'stok_keluar' => $penebusan->jumlah_tabung,
                'stok_akhir' => $newVStock,
                'keterangan' => 'Pembatalan Penebusan DO #' . $penebusan->nomor_do,
            ]);

            if ($penebusan->foto_nota) {
                Storage::disk('public')->delete($penebusan->foto_nota);
            }

            $sa = $penebusan->scheduleAgreement;
            $penebusan->delete();
            
            // Re-check SA status
            $totalTabungInSa = $sa->penebusans()->sum('jumlah_tabung');
            if ($totalTabungInSa < $sa->jumlah_tabung) {
                $sa->update(['status_sa' => 'pending']);
            }

            DB::commit();
            return redirect()->route('penebusan.index')->with('success', 'Penebusan berhasil dihapus dan stok disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus penebusan: ' . $e->getMessage());
        }
    }
}
