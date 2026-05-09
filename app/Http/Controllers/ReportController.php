<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Expense;
use App\Models\Penebusan;
use App\Models\Piutang;
use App\Models\StockMutation;
use App\Models\ReturTabung;
use App\Models\Pangkalan;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\ExpenseCategory;
use App\Exports\PenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Laporan Penjualan
     */
    public function penjualan(Request $request)
    {
        $query = Penjualan::with(['pangkalan', 'truck', 'supir', 'lpgPrice']);

        $this->applyFilters($query, $request);

        $penjualans = $query->latest('tanggal_penjualan')->get();

        // Summary Stats - Updated to use nominal columns for split payment support
        $summary = [
            'total_tabung' => $penjualans->sum('jumlah_tabung'),
            'total_omzet' => $penjualans->sum('total_penjualan'),
            'cash' => $penjualans->sum('nominal_cash'),
            'transfer' => $penjualans->sum('nominal_transfer'),
            'utang' => $penjualans->sum(function($p) {
                return $p->total_penjualan - $p->nominal_cash - $p->nominal_transfer;
            }),
        ];

        // Breakdown per Harga
        $priceBreakdown = $penjualans->groupBy('harga_lpg')->map(function($group) {
            return [
                'harga' => $group->first()->harga_lpg,
                'jumlah' => $group->sum('jumlah_tabung'),
                'subtotal' => $group->sum('total_penjualan'),
            ];
        })->sortByDesc('jumlah');

        $pangalans = Pangkalan::all();
        $trucks = Truck::all();
        $drivers = Driver::all();

        return view('report.penjualan', compact('penjualans', 'summary', 'priceBreakdown', 'pangalans', 'trucks', 'drivers'));
    }

    /**
     * Laporan Pengeluaran
     */
    public function pengeluaran(Request $request)
    {
        $query = Expense::with(['category', 'user'])->where('status_verifikasi', 'disetujui');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pengeluaran', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->latest('tanggal_pengeluaran')->get();

        $categoryBreakdown = $expenses->groupBy('expense_category_id')->map(function($group) {
            return [
                'nama' => $group->first()->category->nama_kategori,
                'total' => $group->sum('nominal'),
                'count' => $group->count(),
            ];
        });

        $totalPengeluaran = $expenses->sum('nominal');

        return view('report.pengeluaran', compact('expenses', 'categoryBreakdown', 'totalPengeluaran'));
    }

    /**
     * Laporan Laba Rugi
     */
    public function labaRugi(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();

        // 1. Revenue (Penjualan) - hanya yang sudah dibayar (lunas/cicilan)
        $totalPenjualan = Penjualan::whereBetween('tanggal_penjualan', [$startDate, $endDate])
            ->whereIn('status_pembayaran', ['lunas', 'cicilan'])
            ->sum('total_penjualan');
        
        // 2. Cost of Goods Sold (Penebusan DO)
        $totalPenebusan = Penebusan::whereBetween('tanggal_penebusan', [$startDate, $endDate])->sum('total_penebusan');
        
        // 3. Operating Expenses
        $expenses = Expense::with('category')
            ->where('status_verifikasi', 'disetujui')
            ->whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->get();
            
        $expenseBreakdown = $expenses->groupBy('expense_category_id')->map(function($group) {
            return [
                'nama' => $group->first()->category->nama_kategori,
                'total' => $group->sum('nominal'),
            ];
        });

        $totalExpense = $expenses->sum('nominal');
        $labaKotor = $totalPenjualan - $totalPenebusan;
        $labaBersih = $labaKotor - $totalExpense;

        return view('report.laba-rugi', compact(
            'totalPenjualan', 
            'totalPenebusan', 
            'expenseBreakdown', 
            'totalExpense', 
            'labaKotor', 
            'labaBersih',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Laporan Piutang
     */
    public function piutang(Request $request)
    {
        $query = Piutang::with(['pangkalan', 'penjualan']);

        if ($request->filled('status')) {
            $query->where('status_piutang', $request->status);
        }

        if ($request->filled('pangkalan_id')) {
            $query->where('pangkalan_id', $request->pangkalan_id);
        }

        $piutangs = $query->latest()->get();

        $summary = [
            'total_piutang' => $piutangs->sum('nominal_piutang'),
            'total_sisa' => $piutangs->sum('sisa_tagihan'),
            'total_bayar' => $piutangs->sum('total_terbayar'),
        ];

        $pangalans = Pangkalan::all();

        return view('report.piutang', compact('piutangs', 'summary', 'pangalans'));
    }

    /**
     * Laporan Stok
     */
    public function stok(Request $request)
    {
        $query = StockMutation::with('user');

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        $mutations = $query->latest('tanggal')->get();
        $returs = ReturTabung::with(['truck', 'supir'])->where('status_retur', 'diterima')->get();

        return view('report.stok', compact('mutations', 'returs'));
    }

    /**
     * Export Penjualan to Excel
     */
    public function exportPenjualan(Request $request)
    {
        return Excel::download(new PenjualanExport($request), 'laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Helper to apply common filters
     */
    private function applyFilters($query, $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penjualan', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('pangkalan_id')) {
            $query->where('pangkalan_id', $request->pangkalan_id);
        }

        if ($request->filled('truck_id')) {
            $query->where('truck_id', $request->truck_id);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        // Supir Knek restriction - Superadmin and Finance sees all
        if (Auth::user()->hasRole('supir_knek') && !Auth::user()->hasAnyRole(['superadmin', 'admin_keuangan'])) {
            $driver = Auth::user()->driver;
            $query->where('driver_id', $driver ? $driver->id : 0);
        }
    }
}
