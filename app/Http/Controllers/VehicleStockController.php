<?php

namespace App\Http\Controllers;

use App\Models\VehicleStock;
use App\Models\VehicleStockHistory;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleStockController extends Controller
{
    // Index method removed as it is now handled by StockController.

    public function returnStock(Request $request, Truck $truck)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $vehicleStock = VehicleStock::where('truck_id', $truck->id)->first();
            
            if (!$vehicleStock || $vehicleStock->stok_saat_ini <= 0) {
                return back()->with('error', 'Tidak ada stok di kendaraan ini untuk dikembalikan.');
            }

            $jumlahKembali = $vehicleStock->stok_saat_ini;

            // 1. Tambah stok gudang
            $gudang = \App\Models\StockSummary::first();
            if (!$gudang) {
                $gudang = \App\Models\StockSummary::create(['stok_saat_ini' => 0]);
            }
            $gudang->stok_saat_ini += $jumlahKembali;
            $gudang->save();

            // 2. Catat Mutasi Gudang
            \App\Models\StockMutation::create([
                'tanggal' => now(),
                'jenis_mutasi' => 'retur_gudang',
                'referensi' => 'PENGEMBALIAN-TRUK-' . $truck->nomor_polisi,
                'lokasi_asal' => 'Kendaraan ' . $truck->nomor_polisi,
                'lokasi_tujuan' => 'Gudang Utama',
                'stok_masuk' => $jumlahKembali,
                'stok_keluar' => 0,
                'stok_akhir' => $gudang->stok_saat_ini,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
            ]);

            // 3. Catat Mutasi Kendaraan (History)
            VehicleStockHistory::create([
                'truck_id' => $truck->id,
                'tanggal' => now(),
                'jenis_mutasi' => 'retur_gudang',
                'referensi' => 'RETUR-GUDANG-' . date('YmdHis'),
                'stok_masuk' => 0,
                'stok_keluar' => $jumlahKembali,
                'stok_akhir' => 0,
                'keterangan' => 'Dikembalikan ke gudang utama',
            ]);

            // 4. Kosongkan stok kendaraan
            $vehicleStock->stok_saat_ini = 0;
            $vehicleStock->save();

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', "Berhasil mengembalikan $jumlahKembali tabung ke gudang.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
