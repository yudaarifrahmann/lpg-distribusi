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
        $penebusans = Penebusan::where('status_penebusan', 'berhasil')
            ->whereDoesntHave('suratJalan')
            ->latest()
            ->get();
        $supirs = Driver::where('role_pekerjaan', 'supir')
            ->where('status', 'aktif')
            ->whereNotNull('truck_id')
            ->with('truck')
            ->get();
        $kneks = Driver::where('role_pekerjaan', 'knek')->where('status', 'aktif')->get();
        $stokGudang = \App\Models\StockSummary::first()?->stok_saat_ini ?? 0;

        return view('surat-jalan.create', compact('penebusans', 'supirs', 'kneks', 'stokGudang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuratJalanRequest $request)
    {
        $data = $request->validated();
        
        DB::beginTransaction();
        try {
            // 1. Handle Supir & Knek Tembak
            if ($data['driver_id'] === 'tembak') {
                $data['driver_id'] = null;
                $data['is_supir_tembak'] = true;
                // Since Truck is now automatic, we need to know which truck for supir tembak.
                // If the user removed the truck field, we might need a default or error out.
                // However, I'll try to find if a truck was passed (maybe hidden) or just error for now.
                if (!isset($data['truck_id'])) {
                    throw new \Exception('Supir tembak memerlukan pemilihan Truck Armada. Silakan hubungi pengembang.');
                }
            } else {
                $driver = Driver::findOrFail($data['driver_id']);
                $data['truck_id'] = $driver->truck_id;
                if (!$data['truck_id']) {
                    throw new \Exception('Supir yang dipilih tidak memiliki Truck Armada default.');
                }
            }

            if ($data['knek_id'] === 'tembak') {
                $data['knek_id'] = null;
                $data['is_knek_tembak'] = true;
            }

            // 2. If DO is selected, we keep the muat_dari_gudang as false by default
            if ($request->filled('penebusan_id')) {
                $penebusan = Penebusan::findOrFail($request->penebusan_id);
                $data['muat_dari_gudang'] = false; 
            }

            // 2. Upload photo if exists
            if ($request->hasFile('foto_surat_jalan')) {
                $data['foto_surat_jalan'] = $request->file('foto_surat_jalan')->store('surat-jalan', 'public');
            }

            // 3. Create SJ
            $data['status_perjalanan'] = $request->action === 'berangkat' ? 'berangkat' : 'persiapan';
            $sj = SuratJalan::create($data);

            // 4. Handle Stock Movement ONLY if "Muat dari Gudang" is checked AND NO DO is selected
            if ($sj->muat_dari_gudang && !$sj->penebusan_id) {
                // Check Warehouse Stock
                $summary = StockSummary::first();
                if (!$summary || $summary->stok_saat_ini < $sj->jumlah_tabung) {
                    throw new \Exception('Stok gudang tidak mencukupi untuk memuat barang. Stok saat ini: ' . ($summary ? $summary->stok_saat_ini : 0));
                }

                // Decrease Warehouse Stock
                $oldWarehouseStock = $summary->stok_saat_ini;
                $newWarehouseStock = $oldWarehouseStock - $sj->jumlah_tabung;
                $summary->update(['stok_saat_ini' => $newWarehouseStock]);

                // Create Warehouse History
                StockHistory::create([
                    'tanggal' => $sj->tanggal_berangkat,
                    'jenis_transaksi' => 'surat_jalan',
                    'referensi' => $sj->nomor_surat_jalan,
                    'stok_masuk' => 0,
                    'stok_keluar' => $sj->jumlah_tabung,
                    'stok_akhir' => $newWarehouseStock,
                    'keterangan' => 'Muat dari Gudang ke Truk (SJ #' . $sj->nomor_surat_jalan . ')',
                ]);

                // Increase Vehicle Stock
                $vStock = VehicleStock::firstOrCreate(
                    ['truck_id' => $sj->truck_id],
                    ['stok_saat_ini' => 0]
                );
                $oldVStock = $vStock->stok_saat_ini;
                $newVStock = $oldVStock + $sj->jumlah_tabung;
                $vStock->update(['stok_saat_ini' => $newVStock]);

                // Create Vehicle History
                VehicleStockHistory::create([
                    'tanggal' => $sj->tanggal_berangkat,
                    'truck_id' => $sj->truck_id,
                    'jenis_mutasi' => 'distribusi_ke_truk',
                    'referensi' => $sj->nomor_surat_jalan,
                    'stok_masuk' => $sj->jumlah_tabung,
                    'stok_keluar' => 0,
                    'stok_akhir' => $newVStock,
                    'keterangan' => 'Muat barang dari gudang (SJ #' . $sj->nomor_surat_jalan . ')',
                ]);
            }
            
            DB::commit();
            $msg = $sj->status_perjalanan === 'berangkat' ? 'Surat Jalan berhasil diterbitkan dan truk telah berangkat.' : 'Surat Jalan disimpan dalam tahap persiapan.';
            return redirect()->route('surat-jalan.index')->with('success', $msg . ($sj->muat_dari_gudang ? ' Barang dimuat dari gudang.' : ''));

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
            // Rollback stock if loaded from warehouse
            if ($suratJalan->muat_dari_gudang) {
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
                    'keterangan' => 'Pembatalan SJ (Kembali ke Gudang) #' . $suratJalan->nomor_surat_jalan,
                ]);

                $vStock = VehicleStock::where('truck_id', $suratJalan->truck_id)->first();
                if ($vStock) {
                    $newVStock = $vStock->stok_saat_ini - $suratJalan->jumlah_tabung;
                    $vStock->update(['stok_saat_ini' => $newVStock]);

                    VehicleStockHistory::create([
                        'tanggal' => now(),
                        'truck_id' => $suratJalan->truck_id,
                        'jenis_mutasi' => 'retur_gudang',
                        'referensi' => 'BATAL-' . $suratJalan->nomor_surat_jalan,
                        'stok_masuk' => 0,
                        'stok_keluar' => $suratJalan->jumlah_tabung,
                        'stok_akhir' => $newVStock,
                        'keterangan' => 'Pembatalan SJ (Stok kembali ke gudang)',
                    ]);
                }
            }
            
            if ($suratJalan->foto_surat_jalan) {
                Storage::disk('public')->delete($suratJalan->foto_surat_jalan);
            }

            $suratJalan->delete();
            DB::commit();

            return redirect()->route('surat-jalan.index')->with('success', 'Surat Jalan dibatalkan' . ($suratJalan->muat_dari_gudang ? ' dan stok dikembalikan ke gudang.' : '.'));
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

    /**
     * Download Surat Jalan as PDF
     */
    public function download(SuratJalan $suratJalan)
    {
        $suratJalan->load(['truck', 'supir', 'knek', 'penebusan']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat-jalan.print', compact('suratJalan'));
        return $pdf->download('surat-jalan-' . $suratJalan->nomor_surat_jalan . '.pdf');
    }

    /**
     * Update the status of the surat jalan
     */
    public function updateStatus(Request $request, SuratJalan $suratJalan)
    {
        $request->validate([
            'status_perjalanan' => 'required|in:persiapan,berangkat,selesai,dibatalkan',
        ]);

        $oldStatus = $suratJalan->status_perjalanan;
        $newStatus = $request->status_perjalanan;

        // Prevent status changes from certain states
        if ($oldStatus === 'selesai' && !Auth::user()->hasRole('superadmin')) {
            return back()->with('error', 'Surat Jalan yang sudah selesai tidak dapat diubah statusnya.');
        }

        if ($oldStatus === 'retur') {
            return back()->with('error', 'Surat Jalan yang sudah di-retur tidak dapat diubah statusnya.');
        }

        // Handle cancellation (Dibatalkan)
        if ($newStatus === 'dibatalkan') {
            DB::beginTransaction();
            try {
                // Rollback stock if loaded from warehouse and not yet departed
                if ($suratJalan->muat_dari_gudang && $oldStatus === 'persiapan') {
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
                        'keterangan' => 'Pembatalan SJ (Kembali ke Gudang) #' . $suratJalan->nomor_surat_jalan,
                    ]);

                    $vStock = VehicleStock::where('truck_id', $suratJalan->truck_id)->first();
                    if ($vStock) {
                        $newVStock = $vStock->stok_saat_ini - $suratJalan->jumlah_tabung;
                        $vStock->update(['stok_saat_ini' => $newVStock]);

                        VehicleStockHistory::create([
                            'tanggal' => now(),
                            'truck_id' => $suratJalan->truck_id,
                            'jenis_mutasi' => 'retur_gudang',
                            'referensi' => 'BATAL-' . $suratJalan->nomor_surat_jalan,
                            'stok_masuk' => 0,
                            'stok_keluar' => $suratJalan->jumlah_tabung,
                            'stok_akhir' => $newVStock,
                            'keterangan' => 'Pembatalan SJ (Stok kembali ke gudang)',
                        ]);
                    }
                }

                $suratJalan->update(['status_perjalanan' => 'dibatalkan']);
                DB::commit();
                return back()->with('success', 'Status Surat Jalan berhasil diubah menjadi Dibatalkan.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
            }
        }

        // Handle other status changes
        $suratJalan->update(['status_perjalanan' => $newStatus]);

        $statusLabel = ucfirst(str_replace('_', ' ', $newStatus));
        return back()->with('success', "Status Surat Jalan berhasil diubah menjadi {$statusLabel}.");
    }
}
