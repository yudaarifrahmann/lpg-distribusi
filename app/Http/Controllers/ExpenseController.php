<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseAttachment;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'user', 'attachments']);

        // Role based filtering
        if (Auth::user()->hasRole('supir_knek')) {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('search')) {
            $query->where('nama_pengeluaran', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pengeluaran', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->latest('tanggal_pengeluaran')->paginate(15);
        $categories = ExpenseCategory::all();

        // Dashboard Stats for Index
        $today = Carbon::today();
        $thisMonth = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        
        $totalHariIni = Expense::whereDate('tanggal_pengeluaran', $today)->where('status_verifikasi', '!=', 'ditolak')->sum('nominal');
        $totalBulanIni = Expense::whereBetween('tanggal_pengeluaran', $thisMonth)->where('status_verifikasi', '!=', 'ditolak')->sum('nominal');

        return view('expense.index', compact('expenses', 'categories', 'totalHariIni', 'totalBulanIni'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $data = $request->validated();
        
        DB::beginTransaction();
        try {
            $data['user_id'] = Auth::id();
            $data['status_verifikasi'] = 'disetujui';
            $data['verified_by'] = Auth::id();
            $data['verified_at'] = now();
            
            $expense = Expense::create($data);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('expenses', 'public');
                    ExpenseAttachment::create([
                        'expense_id' => $expense->id,
                        'path_file' => $path,
                        'nama_file' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil diajukan dan sedang menunggu verifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pengeluaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['category', 'user', 'attachments', 'verifier']);
        return view('expense.show', compact('expense'));
    }

    /**
     * Update verification status.
     */
    public function verify(Request $request, Expense $expense)
    {
        if (!Auth::user()->can('edit pengeluaran')) {
            return back()->with('error', 'Anda tidak memiliki akses untuk verifikasi.');
        }

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $expense->update([
            'status_verifikasi' => $request->status,
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Status pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->status_verifikasi != 'pending' && !Auth::user()->hasRole('superadmin')) {
            return back()->with('error', 'Hanya pengeluaran pending yang dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            foreach ($expense->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path_file);
            }
            $expense->delete();
            DB::commit();
            return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengeluaran: ' . $e->getMessage());
        }
    }
}
