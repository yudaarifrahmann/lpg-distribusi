<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Truck::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_truk', 'like', '%' . $search . '%')
                  ->orWhere('nomor_polisi', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $query->where('status_kendaraan', $status);
        }

        $query->orderBy('nama_truk', 'asc');

        $trucks = $query->paginate(10);

        return view('master-data.truck.index', compact('trucks', 'search', 'status'));
    }

    public function store(StoreTruckRequest $request)
    {
        $validated = $request->validated();
        
        foreach ($validated['trucks'] as $truckData) {
            Truck::create($truckData);
        }

        return redirect()->route('truck.index')
                        ->with('success', count($validated['trucks']) . ' Truk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {
        return view('master-data.truck.show', compact('truck'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTruckRequest $request, Truck $truck)
    {
        $truck->update($request->validated());

        return redirect()->route('truck.index')
                        ->with('success', 'Truk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Truck $truck)
    {
        $truck->delete();

        return redirect()->route('truck.index')
                        ->with('success', 'Truk berhasil dihapus');
    }
}
