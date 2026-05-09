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
        $trucks = Truck::where('status_kendaraan', 'aktif')->get();
        $drivers = Driver::where('status', 'aktif')->get();
        $sas = ScheduleAgreement::where('status_sa', 'pending')->get();
        
        $selectedSaId = $request->sa_id;
        $selectedSa = null;
        if ($selectedSaId) {
            $selectedSa = ScheduleAgreement::find($selectedSaId);
        }

        return view('penebusan.create', compact('trucks', 'drivers', 'sas', 'selectedSa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenebusanRequest $request)
    {
        $data = $request->validated();
        
        // 1 DO = 560 tabung
        // prompt says "harga default: Rp6.487.298 per DO"
        // Here we assume 1 penebusan = 1 DO (standard flow) or we can allow multiple if UI supports it.
        // The prompt says "multiple DO dalam satu SA", but usually 1 record = 1 nomor DO.
        
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
