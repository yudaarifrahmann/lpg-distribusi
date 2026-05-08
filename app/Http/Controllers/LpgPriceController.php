<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLpgPriceRequest;
use App\Http\Requests\UpdateLpgPriceRequest;
use App\Models\LpgPrice;
use Illuminate\Http\Request;

class LpgPriceController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = LpgPrice::query();

        if ($search) {
            $query->where('nama_harga', 'like', '%' . $search . '%')
                  ->orWhere('harga', 'like', '%' . $search . '%');
        }

        if ($status) {
            $query->where('status', $status);
        }

        $query->orderBy('harga', 'asc');

        $lpgPrices = $query->paginate(10);

        return view('master-data.lpg-price.index', compact('lpgPrices', 'search', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLpgPriceRequest $request)
    {
        LpgPrice::create($request->validated());

        return redirect()->route('lpg-price.index')
                        ->with('success', 'Harga LPG berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLpgPriceRequest $request, LpgPrice $lpgPrice)
    {
        $lpgPrice->update($request->validated());

        return redirect()->route('lpg-price.index')
                        ->with('success', 'Harga LPG berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LpgPrice $lpgPrice)
    {
        $lpgPrice->delete();

        return redirect()->route('lpg-price.index')
                        ->with('success', 'Harga LPG berhasil dihapus');
    }
}
