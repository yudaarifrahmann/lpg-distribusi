<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Models\Penjualan;
use App\Models\Piutang;
use App\Models\SuratJalan;
use App\Models\Pangkalan;
use App\Models\LpgPrice;
use App\Models\VehicleStock;
use App\Models\VehicleStockHistory;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    use LogActivity;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penjualan::with(['pangkalan', 'truck', 'supir', 'lpgPrice', 'returs']);

        // Role based filtering - Superadmin and Finance sees all
        if (Auth::user()->hasRole('supir_knek') && !Auth::user()->hasAnyRole(['superadmin', 'admin_keuangan'])) {
            $driver = Auth::user()->driver;
            if ($driver) {
                $query->where('driver_id', $driver->id);
            } else {
                $query->where('id', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where('nomor_invoice', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('pangkalan_id')) {
            $query->where('pangkalan_id', $request->pangkalan_id);
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penjualan', [$request->start_date, $request->end_date]);
        }

        $penjualans = $query->latest('tanggal_penjualan')->paginate(15);
        $pangkalans = Pangkalan::where('status', 'aktif')->get();

        return view('penjualan.index', compact('penjualans', 'pangkalans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $querySj = SuratJalan::with(['truck.vehicleStock', 'supir'])->where('status_perjalanan', 'berangkat');
        
        // Supir only sees their own active SJ
        if (Auth::user()->hasRole('supir_knek')) {
            $driver = Auth::user()->driver;
            if ($driver) {
                $querySj->where(function($q) use ($driver) {
                    $q->where('driver_id', $driver->id)->orWhere('knek_id', $driver->id);
                });
            } else {
                $querySj->where('id', 0);
            }
        }

        $suratJalans = $querySj->get();
        $pangkalans = Pangkalan::where('status', 'aktif')->get();
        $prices = LpgPrice::where('status', 'aktif')->get();

        return view('penjualan.create', compact('suratJalans', 'pangkalans', 'prices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenjualanRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $sj       = SuratJalan::findOrFail($data['surat_jalan_id']);
            $price    = LpgPrice::findOrFail($data['lpg_price_id']);
            $pangkalan = $this->resolvePangkalan($data);

            // 1. Check Vehicle Stock
            $vStock = VehicleStock::where('truck_id', $sj->truck_id)->first();
            if (!$vStock || $vStock->stok_saat_ini < $data['jumlah_tabung']) {
                throw new \Exception('Stok di kendaraan tidak mencukupi. Stok saat ini: ' . ($vStock ? $vStock->stok_saat_ini : 0));
            }

            // 2. Hitung nominal
            $total          = $price->harga * $data['jumlah_tabung'];
            $nominalCash     = (float) ($data['nominal_cash'] ?? 0);
            $nominalTransfer = (float) ($data['nominal_transfer'] ?? 0);
            $nominalUtang    = max(0, $total - $nominalCash - $nominalTransfer);

            // 3. Tentukan metode & status
            $paidComponents = [];
            if ($nominalCash > 0)     $paidComponents[] = 'cash';
            if ($nominalTransfer > 0) $paidComponents[] = 'transfer';
            if ($nominalUtang > 0)    $paidComponents[] = 'utang';

            $metode = count($paidComponents) > 1 ? 'split' : ($paidComponents[0] ?? 'cash');
            $statusPembayaran = ($nominalUtang > 0) ? 'belum_lunas' : 'lunas';
            $statusTransfer   = ($nominalTransfer > 0) ? 'pending' : null;

            // 4. Invoice
            $invoice = 'INV-' . Carbon::now()->format('YmdHis') . rand(10, 99);

            // 5. Simpan penjualan
            $penjualan = Penjualan::create([
                'nomor_invoice'    => $invoice,
                'tanggal_penjualan'=> $data['tanggal_penjualan'],
                'surat_jalan_id'   => $sj->id,
                'truck_id'         => $sj->truck_id,
                'driver_id'        => $sj->driver_id,
                'pangkalan_id'     => $pangkalan->id,
                'lpg_price_id'     => $price->id,
                'jumlah_tabung'    => $data['jumlah_tabung'],
                'harga_satuan'     => $price->harga,
                'total_penjualan'  => $total,
                'nominal_cash'     => $nominalCash,
                'nominal_transfer' => $nominalTransfer,
                'metode_pembayaran'=> $metode,
                'status_pembayaran'=> $statusPembayaran,
                'status_transfer'  => $statusTransfer,
                'catatan'          => $data['catatan'] ?? null,
            ]);

            // 6. Kurangi stok kendaraan
            $newVStock = $vStock->stok_saat_ini - $data['jumlah_tabung'];
            $vStock->update(['stok_saat_ini' => $newVStock]);

            // 7. History kendaraan
            VehicleStockHistory::create([
                'tanggal'     => $data['tanggal_penjualan'],
                'truck_id'    => $sj->truck_id,
                'jenis_mutasi'=> 'penjualan',
                'referensi'   => $invoice,
                'stok_masuk'  => 0,
                'stok_keluar' => $data['jumlah_tabung'],
                'stok_akhir'  => $newVStock,
                'keterangan'  => 'Penjualan ke Pangkalan: ' . $pangkalan->nama_pangkalan,
            ]);

            // 8. Buat Piutang jika ada sisa tagihan
            if ($nominalUtang > 0) {
                Piutang::create([
                    'penjualan_id'        => $penjualan->id,
                    'pangkalan_id'        => $pangkalan->id,
                    'nominal_piutang'     => $nominalUtang,
                    'sisa_tagihan'        => $nominalUtang,
                    'tanggal_jatuh_tempo' => $data['tanggal_jatuh_tempo'] ?? Carbon::now()->addDays(14),
                    'status_piutang'      => 'belum_bayar',
                ]);
            }

            // 9. Handle Retur if exists
            if ($request->filled('jumlah_retur') && $request->jumlah_retur > 0) {
                $penjualan->returs()->create([
                    'tanggal_retur' => $penjualan->tanggal_penjualan,
                    'surat_jalan_id' => $sj->id,
                    'truck_id' => $sj->truck_id,
                    'driver_id' => $sj->driver_id,
                    'jumlah_retur' => $request->jumlah_retur,
                    'kondisi_tabung' => $request->kondisi_tabung,
                    'keterangan' => 'Retur saat penjualan: ' . ($request->catatan ?? 'Bocor/Rusak'),
                    'status_retur' => 'pending',
                ]);
            }

            self::log('Input Penjualan: ' . $invoice, 'penjualan', null, $penjualan->toArray());

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Transaksi Penjualan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function resolvePangkalan(array $data): Pangkalan
    {
        if (!empty($data['pangkalan_id'])) {
            return Pangkalan::findOrFail($data['pangkalan_id']);
        }

        $namaPangkalan = trim($data['pangkalan_nama']);
        $pangkalan = Pangkalan::withTrashed()
            ->where('nama_pangkalan', $namaPangkalan)
            ->first();

        if ($pangkalan) {
            if ($pangkalan->trashed()) {
                $pangkalan->restore();
            }

            if ($pangkalan->status !== 'aktif') {
                $pangkalan->update(['status' => 'aktif']);
            }

            return $pangkalan;
        }

        return Pangkalan::create([
            'nama_pangkalan' => $namaPangkalan,
            'nama_pemilik'   => $namaPangkalan,
            'alamat'         => '-',
            'no_hp'          => '-',
            'status'         => 'aktif',
        ]);
    }

    /**
     * Verify a pending bank transfer for a sale.
     * Only finance admin should be able to call this.
     */
    public function verifyTransfer(Penjualan $penjualan)
    {
        if ($penjualan->status_transfer !== 'pending') {
            return back()->with('error', 'Transfer ini tidak dalam status pending.');
        }

        $penjualan->update(['status_transfer' => 'verified']);
        
        // self::log('Verifikasi Transfer: ' . $penjualan->nomor_invoice, 'penjualan', ['status_transfer' => 'pending'], $penjualan->toArray());

        return redirect()->route('piutang.index')->with('success', 'Transfer untuk invoice ' . $penjualan->nomor_invoice . ' berhasil diverifikasi. Uang resmi masuk ke kas.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['pangkalan', 'truck', 'supir', 'lpgPrice', 'suratJalan', 'piutang']);
        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        return view('penjualan.edit', compact('penjualan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan)
    {
        $oldData = $penjualan->toArray();
        $penjualan->update($request->validated());
        
        // If status becomes lunas, we should also update piutang if it exists
        if ($penjualan->status_pembayaran == 'lunas' && $penjualan->piutang) {
            $penjualan->piutang->update([
                'sisa_tagihan' => 0,
                'status_piutang' => 'lunas'
            ]);
        }

        self::log('Update Penjualan: ' . $penjualan->nomor_invoice, 'penjualan', $oldData, $penjualan->toArray());

        return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        // For audit trail, deletion is usually restricted, but I'll implement rollback if superadmin
        if (!Auth::user()->hasRole('superadmin')) {
            return back()->with('error', 'Hanya SuperAdmin yang dapat menghapus transaksi penjualan.');
        }

        DB::beginTransaction();
        try {
            // Rollback vehicle stock
            $vStock = VehicleStock::where('truck_id', $penjualan->truck_id)->first();
            $newVStock = $vStock->stok_saat_ini + $penjualan->jumlah_tabung;
            $vStock->update(['stok_saat_ini' => $newVStock]);

            VehicleStockHistory::create([
                'tanggal' => now(),
                'truck_id' => $penjualan->truck_id,
                'jenis_mutasi' => 'distribusi_ke_truk',
                'referensi' => 'BATAL-' . $penjualan->nomor_invoice,
                'stok_masuk' => $penjualan->jumlah_tabung,
                'stok_keluar' => 0,
                'stok_akhir' => $newVStock,
                'keterangan' => 'Pembatalan Transaksi ' . $penjualan->nomor_invoice,
            ]);

            $oldData = $penjualan->toArray();
            $penjualan->delete();

            self::log('Hapus Penjualan: ' . $oldData['nomor_invoice'], 'penjualan', $oldData, null);

            DB::commit();

            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil dibatalkan dan stok kendaraan dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
    public function printRekap(Request $request)
    {
        $query = Penjualan::with(['pangkalan', 'truck', 'supir', 'lpgPrice']);

        // Role based filtering
        if (Auth::user()->hasRole('supir_knek') && !Auth::user()->hasAnyRole(['superadmin', 'admin_keuangan'])) {
            $driver = Auth::user()->driver;
            if ($driver) {
                $query->where('driver_id', $driver->id);
            } else {
                $query->where('id', 0);
            }
        }

        if ($request->filled('pangkalan_id')) {
            $query->where('pangkalan_id', $request->pangkalan_id);
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penjualan', [$request->start_date, $request->end_date]);
        }

        $penjualans = $query->latest('tanggal_penjualan')->get();

        $truckIds = $penjualans->pluck('truck_id')->unique();
        $startDate = $request->start_date ?? ($penjualans->min('tanggal_penjualan')?->format('Y-m-d'));
        $endDate = $request->end_date ?? ($penjualans->max('tanggal_penjualan')?->format('Y-m-d'));

        $totalSisaKembali = 0;
        if ($truckIds->isNotEmpty() && $startDate && $endDate) {
            $totalSisaKembali = \App\Models\VehicleStockHistory::whereIn('truck_id', $truckIds)
                ->where('jenis_mutasi', 'retur_gudang')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('stok_keluar');
        }

        $summary = [
            'total_tabung' => $penjualans->sum('jumlah_tabung'),
            'total_omzet' => $penjualans->sum('total_penjualan'),
            'total_cash' => $penjualans->sum('nominal_cash'),
            'total_transfer' => $penjualans->sum('nominal_transfer'),
            'total_piutang' => $penjualans->sum(fn($p) => $p->total_penjualan - $p->nominal_cash - $p->nominal_transfer),
            'total_retur' => $penjualans->sum(fn($p) => $p->returs->sum('jumlah_retur')),
            'total_retur_gudang' => $penjualans->sum(fn($p) => $p->returs->where('status_retur', 'diterima')->sum('jumlah_retur')),
            'total_sisa_kembali' => $totalSisaKembali,
        ];

        return view('penjualan.print-rekap', compact('penjualans', 'summary'));
    }

    public function print(Penjualan $penjualan)
    {
        $penjualan->load(['pangkalan', 'truck', 'supir', 'lpgPrice', 'piutang']);
        return view('penjualan.print', compact('penjualan'));
    }
}
