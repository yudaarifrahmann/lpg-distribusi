<?php

namespace App\Http\Controllers;

use App\Models\StokTitipan;
use App\Models\HistoriMutasiTitipan;
use App\Services\DepositService;
use App\Exports\DepositMutationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StokTitipanController extends Controller
{
    protected $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    /**
     * Dashboard Stok Titipan
     */
    public function index(Request $request)
    {
        $stocks = StokTitipan::where('branch_id', Auth::user()->branch_id)->get();
        
        $summary = [
            'total_pemilik' => $stocks->count(),
            'total_tabung' => $stocks->sum('total_tabung'),
            'total_tersedia' => $stocks->sum('jumlah_tersedia'),
            'total_dipinjam' => $stocks->sum('jumlah_dipinjam'),
        ];

        $recentMutations = HistoriMutasiTitipan::with('user')
            ->where('branch_id', Auth::user()->branch_id)
            ->latest()
            ->limit(10)
            ->get();

        return view('titipan.index', compact('stocks', 'summary', 'recentMutations'));
    }

    /**
     * Form & Proses Titip Tabung
     */
    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'pemilik_tabung' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->depositService->deposit($validated);
            return redirect()->route('titipan.index')->with('success', 'Titip tabung berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Form & Proses Pinjam Tabung
     */
    public function loan(Request $request)
    {
        $validated = $request->validate([
            'pemilik_tabung' => 'required|string|max:255',
            'peminjam' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->depositService->loan($validated);
            return redirect()->route('titipan.index')->with('success', 'Pinjam tabung berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Form & Proses Pengembalian Tabung
     */
    public function return(Request $request)
    {
        $validated = $request->validate([
            'pemilik_tabung' => 'required|string|max:255',
            'peminjam' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->depositService->return($validated);
            return redirect()->route('titipan.index')->with('success', 'Pengembalian tabung berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Laporan Mutasi Titipan
     */
    public function report(Request $request)
    {
        $query = HistoriMutasiTitipan::with('user')->where('branch_id', Auth::user()->branch_id);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('pemilik_tabung')) {
            $query->where('pemilik_tabung', 'like', '%' . $request->pemilik_tabung . '%');
        }

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        $mutations = $query->latest()->paginate(20);
        $pemiliks = StokTitipan::where('branch_id', Auth::user()->branch_id)->pluck('pemilik_tabung');

        return view('titipan.report', compact('mutations', 'pemiliks'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new DepositMutationExport($request), 'laporan-mutasi-titipan-' . date('Y-m-d') . '.xlsx');
    }
}
