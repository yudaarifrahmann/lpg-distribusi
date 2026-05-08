<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = ExpenseCategory::query();

        if ($search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%')
                  ->orWhere('jenis_kategori', 'like', '%' . $search . '%');
        }

        $query->orderBy('nama_kategori', 'asc');

        $expenseCategories = $query->paginate(10);

        return view('master-data.expense-category.index', compact('expenseCategories', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseCategoryRequest $request)
    {
        ExpenseCategory::create($request->validated());

        return redirect()->route('expense-category.index')
                        ->with('success', 'Kategori pengeluaran berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        $expenseCategory->update($request->validated());

        return redirect()->route('expense-category.index')
                        ->with('success', 'Kategori pengeluaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return redirect()->route('expense-category.index')
                        ->with('success', 'Kategori pengeluaran berhasil dihapus');
    }
}
