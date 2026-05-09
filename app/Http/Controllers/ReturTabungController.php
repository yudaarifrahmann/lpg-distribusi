<?php

namespace App\Http\Controllers;

use App\Models\ReturTabung;
use App\Models\SuratJalan;
use App\Models\VehicleStock;
use App\Models\StockSummary;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturTabungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('view stock') && !Auth::user()->hasRole('supir_knek')) {
            abort(403);
        }

        $query = ReturTabung::with(['suratJalan', 'truck', 'supir', 'verifier']);

        if (Auth::user()->hasRole('supir_knek')) {
            $driver = Auth::user()->driver;
            $query->where('driver_id', $driver ? $driver->id : 0);
        }

        $returs = $query->latest()->paginate(15);
        return view('retur.index', compact('returs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->can('view stock') && !Auth::user()->hasRole('supir_knek')) {
            abort(403);
        }

        $querySj = SuratJalan::with(['truck', 'supir'])->where('status_perjalanan', '!=', 'selesai');
        
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
        return view('retur.create', compact('suratJalans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_retur' => 'required|date',
            'surat_jalan_id' => 'required|exists:surat_jalans,id',
            'jumlah_retur' => 'required|integer|min:1',
            'kondisi_tabung' => 'required|in:baik,rusak,bocor',
            'keterangan' => 'nullable|string',
        ]);

        $sj = SuratJalan::findOrFail($data['surat_jalan_id']);
        
        // Check vehicle stock
        $vStock = VehicleStock::where('truck_id', $sj->truck_id)->first();
        if (!$vStock || $vStock->stok_saat_ini < $data['jumlah_retur']) {
            return back()->with('error', 'Stok di kendaraan tidak mencukupi untuk diretur. Sisa stok: ' . ($vStock->stok_saat_ini ?? 0))->withInput();
        }

        $data['truck_id'] = $sj->truck_id;
        $data['driver_id'] = $sj->driver_id;
        $data['status_retur'] = 'pending';

        ReturTabung::create($data);

        return redirect()->route('retur.index')->with('success', 'Permohonan retur berhasil diajukan.');
    }

    /**
     * Approve return and sync stock.
     */
    public function approve(ReturTabung $retur)
    {
        if (!Auth::user()->can('edit stock')) {
            return back()->with('error', 'Hanya admin yang dapat menyetujui retur.');
        }

        if ($retur->status_retur != 'pending') {
            return back()->with('error', 'Retur sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // 1. Decrease Vehicle Stock
            $vStock = VehicleStock::where('truck_id', $retur->truck_id)->first();
            $newVStock = $vStock->stok_saat_ini - $retur->jumlah_retur;
            $vStock->update(['stok_saat_ini' => $newVStock]);

            // 2. Increase Warehouse Stock
            $stockSummary = StockSummary::first();
            $oldWStock = $stockSummary->stok_saat_ini;
            $newWStock = $oldWStock + $retur->jumlah_retur;
            $stockSummary->update(['stok_saat_ini' => $newWStock]);

            // 3. Log Unified Mutation
            StockMutation::create([
                'tanggal' => now(),
                'jenis_mutasi' => 'retur_gudang',
                'referensi' => 'RET-' . $retur->id,
                'lokasi_asal' => $retur->truck->nomor_polisi,
                'lokasi_tujuan' => 'Gudang Utama',
                'stok_masuk' => $retur->jumlah_retur,
                'stok_keluar' => 0,
                'stok_akhir' => $newWStock,
                'user_id' => Auth::id(),
            ]);

            // 4. Update Retur Status
            $retur->update([
                'status_retur' => 'diterima',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Retur diterima. Stok gudang bertambah dan stok kendaraan berkurang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses retur: ' . $e->getMessage());
        }
    }
}
