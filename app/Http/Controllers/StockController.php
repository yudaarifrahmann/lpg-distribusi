<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use App\Models\StockSummary;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // 1. Gudang Summary
        $summary = StockSummary::first();
        if (!$summary) {
            $summary = StockSummary::create(['stok_saat_ini' => 0]);
        }
        $totalStokGudang = $summary->stok_saat_ini;

        // 2. Kendaraan Summary & List
        $vehicleQuery = \App\Models\VehicleStock::with('truck');
        $truckIds = [];

        if (\Illuminate\Support\Facades\Auth::user()->hasRole('supir_knek')) {
            $driver = \Illuminate\Support\Facades\Auth::user()->driver;
            if ($driver) {
                $truckIds = \App\Models\SuratJalan::where('driver_id', $driver->id)
                    ->orWhere('knek_id', $driver->id)
                    ->pluck('truck_id')
                    ->unique();
                $vehicleQuery->whereIn('truck_id', $truckIds);
            } else {
                $vehicleQuery->where('id', 0);
            }
        }

        $vehicleStocks = $vehicleQuery->get();
        $totalStokKendaraan = $vehicleStocks->sum('stok_saat_ini');
        
        $totalKeseluruhan = $totalStokGudang + $totalStokKendaraan;

        // 3. Mutasi Gudang (Histories)
        $gudangQuery = StockHistory::query();
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $gudangQuery->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        if ($request->filled('jenis')) {
            $gudangQuery->where('jenis_transaksi', $request->jenis);
        }
        $gudangHistories = $gudangQuery->latest()->paginate(15, ['*'], 'gudang_page');

        // 4. Mutasi Kendaraan (Histories)
        $vehicleHistoryQuery = \App\Models\VehicleStockHistory::with('truck');
        if (\Illuminate\Support\Facades\Auth::user()->hasRole('supir_knek')) {
            $vehicleHistoryQuery->whereIn('truck_id', $truckIds);
        }
        if ($request->filled('truck_id')) {
            $vehicleHistoryQuery->where('truck_id', $request->truck_id);
        }
        $vehicleHistories = $vehicleHistoryQuery->latest()->paginate(15, ['*'], 'vehicle_page');

        $trucks = \App\Models\Truck::where('status_kendaraan', 'aktif')->get();

        return view('stock.index', compact(
            'totalStokGudang',
            'totalStokKendaraan',
            'totalKeseluruhan',
            'vehicleStocks',
            'gudangHistories',
            'vehicleHistories',
            'trucks'
        ));
    }
}
