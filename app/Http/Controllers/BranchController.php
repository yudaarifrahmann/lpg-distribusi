<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->paginate(10);
        return view('branch.index', compact('branches'));
    }

    public function create()
    {
        return view('branch.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branches' => 'required|array|min:1',
            'branches.*.name' => 'required|string|max:255',
            'branches.*.address' => 'nullable|string',
            'branches.*.phone' => 'nullable|string|max:20',
        ], [
            'branches.*.name.required' => 'Nama cabang wajib diisi'
        ]);

        foreach ($validated['branches'] as $branchData) {
            Branch::create($branchData);
        }

        return redirect()->route('branch.index')->with('success', count($validated['branches']) . ' Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch)
    {
        return view('branch.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $branch->update($request->all());

        return redirect()->route('branch.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branch.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
