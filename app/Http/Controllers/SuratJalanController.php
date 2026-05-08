<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratJalanRequest;
use App\Http\Requests\UpdateSuratJalanRequest;
use App\Models\SuratJalan;
use App\Models\Penebusan;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\StockSummary;
use App\Models\StockHistory;
use App\Models\VehicleStock;
use App\Models\VehicleStockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratJalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratJalan::with(['truck', 'supir', 'penebusan']);

        // Role based filtering
        if (Auth::user()->hasRole('supir_knek')) {
            $driver = Auth::user()->driver;
            if ($driver) {
                $query->where(function($q) use ($driver) {
                    $q->where('driver_id', $driver->id)
                      ->orWhere('knek_id', $driver->id);
                });
            } else {
                // If user has role but no driver profile, show nothing or error
                $query->where('id', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where('nomor_surat_jalan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status_perjalanan', $request->status);
        }

        $suratJalans = $query->latest('tanggal_berangkat')->paginate(10);

        return view('surat-jalan.index', compact('suratJalans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penebusans = Penebusan::where('status_penebusan', 'berhasil')->latest()->get();
        $trucks = Truck::where('status_kendaraan', 'aktif')->get();
        $supirs = Driver::where('role_pekerjaan', 'supir')->where('status', 'aktif')->get();
        $kneks = Driver::where('role_pekerjaan', 'knek')->where('status', 'aktif')->get();

        return view('surat-jalan.create', compact('penebusans', 'trucks', 'supirs', 'kneks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuratJalanRequest $request)
    {
        $data = $request->validated();
        
        DB::beginTransaction();
        try {
            // 1. Check Warehouse Stock
            $summary = StockSummary::first();
            if (!$summary || $summary->stok_saat_ini < $data['jumlah_tabung']) {
                throw new \Exception('Stok gudang tidak mencukupi. Stok saat ini: ' . ($summary ? $summary->stok_saat_ini : 0));
            }

            // 2. Upload photo if exists
            if ($request->hasFile('foto_surat_jalan')) {
                $data['foto_surat_jalan'] = $request->file('foto_surat_jalan')->store('surat-jalan', 'public');
            }

            // 3. Create SJ
            $data['status_perjalanan'] = 'persiapan';
            $sj = SuratJalan::create($data);

            // 4. Decrease Warehouse Stock
            $oldWarehouseStock = $summary->stok_saat_ini;
            $newWarehouseStock = $oldWarehouseStock - $data['jumlah_tabung'];
            $summary->update(['stok_saat_ini' => $newWarehouseStock]);

            // 5. Create Warehouse History
            StockHistory::create([
                'tanggal' => $sj->tanggal_berangkat,
                'jenis_transaksi' => 'surat_jalan',
                'referensi' => $sj->nomor_surat_jalan,
                'stok_masuk' => 0,
                'stok_keluar' => $data['jumlah_tabung'],
                'stok_akhir' => $newWarehouseStock,
                'keterangan' => 'Pengiriman via SJ #' . $sj->nomor_surat_jalan,
            ]);

            // 6. Increase Vehicle Stock
            $vStock = VehicleStock::firstOrCreate(
                ['truck_id' => $data['truck_id']],
                ['stok_saat_ini' => 0]
            );
            $oldVStock = $vStock->stok_saat_ini;
            $newVStock = $oldVStock + $data['jumlah_tabung'];
            $vStock->update(['stok_saat_ini' => $newVStock]);

            // 7. Create Vehicle History
            VehicleStockHistory::create([
                'tanggal' => $sj->tanggal_berangkat,
                'truck_id' => $data['truck_id'],
                'jenis_mutasi' => 'distribusi_ke_truk',
                'referensi' => $sj->nomor_surat_jalan,
                'stok_masuk' => $data['jumlah_tabung'],
                'stok_keluar' => 0,
                'stok_akhir' => $newVStock,
                'keterangan' => 'Penerimaan stok dari gudang (SJ #' . $sj->nomor_surat_jalan . ')',
            ]);

            DB::commit();
            return redirect()->route('surat-jalan.index')->with('success', 'Surat Jalan berhasil dibuat dan stok telah didistribusikan ke truk.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($data['foto_surat_jalan'])) {
                Storage::disk('public')->delete($data['foto_surat_jalan']);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratJalan $suratJalan)
    {
        $suratJalan->load(['truck', 'supir', 'knek', 'penebusan']);
        return view('surat-jalan.show', compact('suratJalan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratJalan $suratJalan)
    {
        if ($suratJalan->status_perjalanan == 'selesai') {
            return back()->with('error', 'Surat Jalan yang sudah selesai tidak dapat diedit.');
        }

        $trucks = Truck::where('status_kendaraan', 'aktif')->get();
        $supirs = Driver::where('role_pekerjaan', 'supir')->get();
        $kneks = Driver::where('role_pekerjaan', 'knek')->get();

        return view('surat-jalan.edit', compact('suratJalan', 'trucks', 'supirs', 'kneks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSuratJalanRequest $request, SuratJalan $suratJalan)
    {
        $data = $request->validated();
        
        // Logic for updating status only for simplicity if already in transit
        $suratJalan->update($data);

        return redirect()->route('surat-jalan.index')->with('success', 'Surat Jalan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratJalan $suratJalan)
    {
        if ($suratJalan->status_perjalanan != 'persiapan') {
            return back()->with('error', 'Hanya Surat Jalan berstatus persiapan yang dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            // Rollback stock
            $summary = StockSummary::first();
            $newWarehouseStock = $summary->stok_saat_ini + $suratJalan->jumlah_tabung;
            $summary->update(['stok_saat_ini' => $newWarehouseStock]);

            StockHistory::create([
                'tanggal' => now(),
                'jenis_transaksi' => 'surat_jalan',
                'referensi' => 'BATAL-' . $suratJalan->nomor_surat_jalan,
                'stok_masuk' => $suratJalan->jumlah_tabung,
                'stok_keluar' => 0,
                'stok_akhir' => $newWarehouseStock,
                'keterangan' => 'Pembatalan SJ #' . $suratJalan->nomor_surat_jalan,
            ]);

            $vStock = VehicleStock::where('truck_id', $suratJalan->truck_id)->first();
            $newVStock = $vStock->stok_saat_ini - $suratJalan->jumlah_tabung;
            $vStock->update(['stok_saat_ini' => $newVStock]);

            VehicleStockHistory::create([
                'tanggal' => now(),
                'truck_id' => $suratJalan->truck_id,
                'jenis_mutasi' => 'retur_ke_gudang',
                'referensi' => 'BATAL-' . $suratJalan->nomor_surat_jalan,
                'stok_masuk' => 0,
                'stok_keluar' => $suratJalan->jumlah_tabung,
                'stok_akhir' => $newVStock,
                'keterangan' => 'Pembatalan SJ (Stok kembali ke gudang)',
            ]);

            if ($suratJalan->foto_surat_jalan) {
                Storage::disk('public')->delete($suratJalan->foto_surat_jalan);
            }

            $suratJalan->delete();
            DB::commit();

            return redirect()->route('surat-jalan.index')->with('success', 'Surat Jalan dibatalkan dan stok telah dikembalikan ke gudang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan SJ: ' . $e->getMessage());
        }
    }

    public function print(SuratJalan $suratJalan)
    {
        $suratJalan->load(['truck', 'supir', 'knek', 'penebusan']);
        return view('surat-jalan.print', compact('suratJalan'));
    }
}
