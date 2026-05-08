<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePangkalanRequest;
use App\Http\Requests\UpdatePangkalanRequest;
use App\Models\Pangkalan;
use Illuminate\Http\Request;

class PangkalanController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Pangkalan::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pangkalan', 'like', '%' . $search . '%')
                  ->orWhere('nama_pemilik', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $query->orderBy('nama_pangkalan', 'asc');

        $pangkalans = $query->paginate(10);

        return view('master-data.pangkalan.index', compact('pangkalans', 'search', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePangkalanRequest $request)
    {
        Pangkalan::create($request->validated());

        return redirect()->route('pangkalan.index')
                        ->with('success', 'Pangkalan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pangkalan $pangkalan)
    {
        return view('master-data.pangkalan.show', compact('pangkalan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePangkalanRequest $request, Pangkalan $pangkalan)
    {
        $pangkalan->update($request->validated());

        return redirect()->route('pangkalan.index')
                        ->with('success', 'Pangkalan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Pangkalan $pangkalan)
    {
        $pangkalan->delete();

        return redirect()->route('pangkalan.index')
                        ->with('success', 'Pangkalan berhasil dihapus');
    }
}
