<?php

namespace App\Http\Controllers;

use App\Models\VehicleStock;
use App\Models\VehicleStockHistory;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleStockController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleStock::with('truck');

        // Role based filtering
        if (Auth::user()->hasRole('supir_knek')) {
            $driver = Auth::user()->driver;
            if ($driver) {
                // Find trucks assigned to this driver/knek via SJ or simply the truck linked to them if any
                // The prompt says "hanya lihat stok kendaraan miliknya".
                // We'll filter based on the truck currently being used in active SJs or linked.
                // For simplicity, we'll show trucks where they are supir/knek.
                $truckIds = \App\Models\SuratJalan::where('driver_id', $driver->id)
                    ->orWhere('knek_id', $driver->id)
                    ->pluck('truck_id')
                    ->unique();
                
                $query->whereIn('truck_id', $truckIds);
            } else {
                $query->where('id', 0);
            }
        }

        $vehicleStocks = $query->get();

        // History filtering
        $historyQuery = VehicleStockHistory::with('truck');
        if (Auth::user()->hasRole('supir_knek')) {
            $historyQuery->whereIn('truck_id', $truckIds ?? []);
        }

        if ($request->filled('truck_id')) {
            $historyQuery->where('truck_id', $request->truck_id);
        }

        $histories = $historyQuery->latest()->paginate(15);
        $trucks = Truck::where('status_kendaraan', 'aktif')->get();

        return view('vehicle-stock.index', compact('vehicleStocks', 'histories', 'trucks'));
    }
}
