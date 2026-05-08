<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\StockSummary;
use App\Models\VehicleStock;
use App\Models\StockMutation;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::with(['truck', 'user'])->latest()->paginate(15);
        return view('stock-adjustment.index', compact('adjustments'));
    }

    public function create()
    {
        $trucks = Truck::where('status', 'aktif')->get();
        $gudangStock = StockSummary::first()->stok_saat_ini ?? 0;
        return view('stock-adjustment.create', compact('trucks', 'gudangStock'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('superadmin')) {
            abort(403);
        }

        $data = $request->validate([
            'tanggal_adjustment' => 'required|date',
            'lokasi_stok' => 'required|in:gudang,kendaraan',
            'truck_id' => 'required_if:lokasi_stok,kendaraan|nullable|exists:trucks,id',
            'stok_setelah' => 'required|integer|min:0',
            'alasan_penyesuaian' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $stokSebelum = 0;
            $nomorRef = 'ADJ-' . time();

            if ($data['lokasi_stok'] == 'gudang') {
                $stockSummary = StockSummary::first();
                $stokSebelum = $stockSummary->stok_saat_ini;
                $stockSummary->update(['stok_saat_ini' => $data['stok_setelah']]);
                
                $lokasiName = 'Gudang Utama';
            } else {
                $vStock = VehicleStock::firstOrCreate(['truck_id' => $data['truck_id']], ['stok_saat_ini' => 0]);
                $stokSebelum = $vStock->stok_saat_ini;
                $vStock->update(['stok_saat_ini' => $data['stok_setelah']]);
                
                $truck = Truck::find($data['truck_id']);
                $lokasiName = 'Truck: ' . $truck->nomor_polisi;
            }

            $selisih = $data['stok_setelah'] - $stokSebelum;
            
            // 1. Save Adjustment
            StockAdjustment::create([
                'tanggal_adjustment' => $data['tanggal_adjustment'],
                'lokasi_stok' => $data['lokasi_stok'],
                'truck_id' => $data['truck_id'] ?? null,
                'stok_sebelum' => $stokSebelum,
                'stok_setelah' => $data['stok_setelah'],
                'selisih' => $selisih,
                'alasan_penyesuaian' => $data['alasan_penyesuaian'],
                'user_id' => Auth::id(),
            ]);

            // 2. Log Mutation
            StockMutation::create([
                'tanggal' => now(),
                'jenis_mutasi' => 'penyesuaian_stok',
                'referensi' => $nomorRef,
                'lokasi_asal' => $lokasiName,
                'lokasi_tujuan' => $lokasiName,
                'stok_masuk' => $selisih > 0 ? abs($selisih) : 0,
                'stok_keluar' => $selisih < 0 ? abs($selisih) : 0,
                'stok_akhir' => $data['stok_setelah'],
                'user_id' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('stock-adjustment.index')->with('success', 'Penyesuaian stok berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyesuaikan stok: ' . $e->getMessage());
        }
    }
}
