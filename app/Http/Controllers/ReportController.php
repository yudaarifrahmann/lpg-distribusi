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
use App\Models\Branch;
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
        $query = Penjualan::with(['pangkalan', 'truck', 'supir', 'lpgPrice', 'piutang']);

        $this->applyFilters($query, $request);

        $penjualans = $query->latest('tanggal_penjualan')->get();

        // Summary Stats - Updated to use nominal columns for split payment support
        $summary = [
            'total_tabung' => $penjualans->sum('jumlah_tabung'),
            'total_omzet' => $penjualans->sum('total_penjualan'),
            'cash' => $penjualans->sum('nominal_cash'),
            'transfer' => $penjualans->sum('nominal_transfer'),
            'utang' => $penjualans->sum(function($p) {
                // Gunakan sisa tagihan dari piutang jika ada, agar sinkron
                return $p->piutang ? $p->piutang->sisa_tagihan : 0;
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
        $isSuperAdmin = Auth::user()->hasRole('superadmin');
        $branches = $isSuperAdmin ? Branch::all() : collect();

        return view('report.penjualan', compact('penjualans', 'summary', 'priceBreakdown', 'pangalans', 'trucks', 'drivers', 'isSuperAdmin', 'branches'));
    }

    /**
     * Laporan Pengeluaran
     */
    public function pengeluaran(Request $request)
    {
        $isSuperAdmin = Auth::user()->hasRole('superadmin');
        $branchId = Auth::user()->branch_id;
        $selectedBranchId = $request->branch_id;

        if (!$isSuperAdmin) {
            $selectedBranchId = $branchId;
        }

        $query = Expense::with(['category', 'user'])
            ->where('status_verifikasi', 'disetujui')
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId));

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pengeluaran', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->latest('tanggal_pengeluaran')->get();

        $categoryBreakdown = $expenses->groupBy('expense_category_id')->map(function($group) {
            return [
                'nama' => $group->first()->category->nama_kategori ?? 'Tanpa Kategori',
                'total' => $group->sum('nominal'),
                'count' => $group->count(),
            ];
        });

        $totalPengeluaran = $expenses->sum('nominal');
        $branches = $isSuperAdmin ? Branch::all() : collect();

        return view('report.pengeluaran', compact('expenses', 'categoryBreakdown', 'totalPengeluaran', 'isSuperAdmin', 'branches', 'selectedBranchId'));
    }

    /**
     * Laporan Laba Rugi
     */
    public function labaRugi(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
        $selectedBranchId = $request->branch_id;
        $isSuperAdmin = Auth::user()->hasRole('superadmin');
        $userBranchId = Auth::user()->branch_id;

        if (!$isSuperAdmin) {
            $selectedBranchId = $userBranchId;
        }

        // 1. Revenue (Penjualan) - hanya yang sudah dibayar (lunas/cicilan)
        $totalPenjualan = Penjualan::whereBetween('tanggal_penjualan', [$startDate, $endDate])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereIn('status_pembayaran', ['lunas', 'cicilan'])
            ->sum('total_penjualan');
        
        // 2. Cost of Goods Sold (Penebusan DO)
        $totalPenebusan = Penebusan::whereBetween('tanggal_penebusan', [$startDate, $endDate])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->sum('total_penebusan');
        
        // 3. Operating Expenses
        $expenses = Expense::with('category')
            ->where('status_verifikasi', 'disetujui')
            ->whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->get();
            
        $expenseBreakdown = $expenses->groupBy('expense_category_id')->map(function($group) {
            return [
                'nama' => $group->first()->category->nama_kategori ?? 'Lain-lain',
                'total' => $group->sum('nominal'),
            ];
        });

        $totalExpense = $expenses->sum('nominal');
        $labaKotor = $totalPenjualan - $totalPenebusan;
        $labaBersih = $labaKotor - $totalExpense;

        $branches = $isSuperAdmin ? Branch::all() : collect();

        return view('report.laba-rugi', compact(
            'totalPenjualan', 
            'totalPenebusan', 
            'expenseBreakdown', 
            'totalExpense', 
            'labaKotor', 
            'labaBersih',
            'startDate',
            'endDate',
            'branches',
            'isSuperAdmin',
            'selectedBranchId'
        ));
    }

    /**
     * Laporan Piutang
     */
    public function piutang(Request $request)
    {
        $isSuperAdmin = Auth::user()->hasRole('superadmin');
        $branchId = Auth::user()->branch_id;
        $selectedBranchId = $request->branch_id;

        if (!$isSuperAdmin) {
            $selectedBranchId = $branchId;
        }

        $query = Piutang::with(['pangkalan', 'penjualan'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId));

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
        $branches = $isSuperAdmin ? Branch::all() : collect();

        return view('report.piutang', compact('piutangs', 'summary', 'pangalans', 'isSuperAdmin', 'branches', 'selectedBranchId'));
    }

    /**
     * Laporan Stok
     */
    public function stok(Request $request)
    {
        $isSuperAdmin = Auth::user()->hasRole('superadmin');
        $branchId = Auth::user()->branch_id;
        $selectedBranchId = $request->branch_id;

        if (!$isSuperAdmin) {
            $selectedBranchId = $branchId;
        }

        $query = StockMutation::with('user')
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId));

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        $mutations = $query->latest('tanggal')->get();
        $returs = ReturTabung::with(['truck', 'supir'])
            ->where('status_retur', 'diterima')
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->get();

        $branches = $isSuperAdmin ? Branch::all() : collect();

        return view('report.stok', compact('mutations', 'returs', 'isSuperAdmin', 'branches', 'selectedBranchId'));
    }

    /**
     * Export Penjualan to Excel
     */
    public function exportPenjualan(Request $request)
    {
        return Excel::download(new PenjualanExport($request), 'laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export Global Report to Excel
     */
    public function exportGlobal(Request $request)
    {
        return Excel::download(new \App\Exports\GlobalReportExport($request), 'laporan-global-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Laporan Global (Semua Pemasukan, Pengeluaran, dan Retur Gudang)
     */
    public function globalReport(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadmin');
        $branchId = $user->branch_id;

        $startDate = $request->filled('start_date') ? $request->start_date : Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->filled('end_date') ? $request->end_date : Carbon::now()->endOfMonth()->toDateString();
        $search = $request->search;
        $paymentMethod = $request->payment_method;
        $selectedBranchId = $request->branch_id;

        // Force branch if not superadmin
        if (!$isSuperAdmin) {
            $selectedBranchId = $branchId;
        }

        // Get Penjualan (Income)
        $penjualanQuery = Penjualan::with(['truck', 'supir', 'branch', 'piutang'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereBetween('tanggal_penjualan', [$startDate, $endDate]);

        if ($search) {
            $penjualanQuery->where(function($q) use ($search) {
                $q->whereHas('truck', fn($t) => $t->where('nomor_polisi', 'like', "%$search%"))
                  ->orWhereHas('supir', fn($s) => $s->where('nama', 'like', "%$search%"))
                  ->orWhere('nomor_invoice', 'like', "%$search%");
            });
        }

        if ($paymentMethod) {
            if ($paymentMethod === 'cash') $penjualanQuery->where('nominal_cash', '>', 0);
            elseif ($paymentMethod === 'transfer') $penjualanQuery->where('nominal_transfer', '>', 0);
            elseif ($paymentMethod === 'utang') $penjualanQuery->whereRaw('(total_penjualan - nominal_cash - nominal_transfer) > 0');
        }

        $penjualans = $penjualanQuery->get()->map(function($item) {
            return [
                'type' => 'penjualan',
                'tanggal' => $item->tanggal_penjualan,
                'cabang' => $item->branch->name ?? 'PT. FARHAN ENERGI GASINDO',
                'nama_truk' => $item->truck->nomor_polisi ?? '-',
                'nama_supir' => $item->supir->nama ?? '-',
                'nominal' => $item->total_penjualan,
                'cash' => $item->nominal_cash,
                'transfer' => $item->nominal_transfer,
                'utang' => $item->piutang ? $item->piutang->sisa_tagihan : 0,
                'jumlah_tabung' => $item->jumlah_tabung,
                'keterangan' => 'Penjualan - ' . $item->nomor_invoice,
                'status' => 'Pemasukan',
            ];
        });

        // Get Expenses (Pengeluaran)
        $expenseQuery = Expense::with(['category', 'user', 'branch'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->where('status_verifikasi', 'disetujui')
            ->whereBetween('tanggal_pengeluaran', [$startDate, $endDate]);

        if ($search) {
            $expenseQuery->where(function($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$search%"))
                  ->orWhere('nama_pengeluaran', 'like', "%$search%");
            });
        }
        
        // Expenses are usually cash, if filtering by TF or Utang, they might be empty unless specific logic exists
        if ($paymentMethod && $paymentMethod !== 'cash') {
            $expenseQuery->where('id', 0); // Hide if filtering for TF/Utang
        }

        $expenses = $expenseQuery->get()->map(function($item) {
            return [
                'type' => 'expense',
                'tanggal' => $item->tanggal_pengeluaran,
                'cabang' => $item->branch->name ?? 'PT. FARHAN ENERGI GASINDO',
                'nama_truk' => '-',
                'nama_supir' => $item->user->name ?? '-',
                'nominal' => $item->nominal,
                'cash' => $item->nominal,
                'transfer' => 0,
                'utang' => 0,
                'jumlah_tabung' => 0,
                'keterangan' => 'Pengeluaran - ' . ($item->category ? $item->category->nama_kategori : $item->nama_pengeluaran),
                'status' => 'Pengeluaran',
            ];
        });

        // Get Penebusan DO (Cost of Goods Sold / Purchasing)
        $penebusanQuery = Penebusan::with(['truck', 'driver', 'branch', 'scheduleAgreement'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereBetween('tanggal_penebusan', [$startDate, $endDate]);

        if ($search) {
            $penebusanQuery->where(function($q) use ($search) {
                $q->where('nomor_do', 'like', "%$search%")
                  ->orWhereHas('truck', fn($t) => $t->where('nomor_polisi', 'like', "%$search%"))
                  ->orWhereHas('driver', fn($d) => $d->where('nama', 'like', "%$search%"));
            });
        }

        $penebusans = $penebusanQuery->get()->map(function($item) {
            return [
                'type' => 'penebusan',
                'tanggal' => $item->tanggal_penebusan,
                'cabang' => $item->branch->name ?? 'PT. FARHAN ENERGI GASINDO',
                'nama_truk' => $item->truck->nomor_polisi ?? '-',
                'nama_supir' => $item->driver->nama ?? '-',
                'nominal' => $item->total_penebusan,
                'cash' => $item->total_penebusan, // Usually paid out
                'transfer' => 0,
                'utang' => 0,
                'jumlah_tabung' => $item->jumlah_tabung,
                'keterangan' => 'Penebusan DO - ' . $item->nomor_do,
                'status' => 'Penebusan',
            ];
        });

        // Get Retur Tabung (Warehouse Returns)
        $returQuery = ReturTabung::with(['truck', 'supir', 'branch'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereBetween('tanggal_retur', [$startDate, $endDate])
            ->whereIn('status_retur', ['pending', 'diterima']);

        if ($search) {
            $returQuery->where(function($q) use ($search) {
                $q->whereHas('truck', fn($t) => $t->where('nomor_polisi', 'like', "%$search%"))
                  ->orWhereHas('supir', fn($s) => $s->where('nama', 'like', "%$search%"));
            });
        }

        if ($paymentMethod) {
            $returQuery->where('id', 0); // Returns have no payment
        }

        $returs = $returQuery->get()->map(function($item) {
                return [
                    'type' => 'retur',
                    'tanggal' => $item->tanggal_retur,
                    'cabang' => $item->branch->name ?? 'PT. FARHAN ENERGI GASINDO',
                    'nama_truk' => $item->truck->nomor_polisi ?? '-',
                    'nama_supir' => $item->supir->nama ?? '-',
                    'nominal' => 0,
                    'cash' => 0,
                    'transfer' => 0,
                    'utang' => 0,
                    'jumlah_tabung' => $item->jumlah_retur,
                    'keterangan' => 'Retur Tabung - ' . $item->kondisi_tabung . ' (' . ucfirst($item->status_retur) . ')',
                    'status' => 'Retur',
                ];
            });

        // Combine all data
        $allData = collect()
            ->merge($penjualans)
            ->merge($expenses)
            ->merge($penebusans)
            ->merge($returs)
            ->sortBy('tanggal');

        // Calculate Summary
        $summary = [
            'total_pemasukan' => $penjualans->sum('nominal'),
            'total_pengeluaran' => $expenses->sum('nominal') + $penebusans->sum('nominal'),
            'total_retur_tabung' => $returs->sum('jumlah_tabung'),
            'total_tabung_penjualan' => $penjualans->sum('jumlah_tabung'),
            'total_tabung_penebusan' => $penebusans->sum('jumlah_tabung'),
            'saldo' => $penjualans->sum('nominal') - ($expenses->sum('nominal') + $penebusans->sum('nominal')),
            'total_cash' => $penjualans->sum('cash'),
            'total_transfer' => $penjualans->sum('transfer'),
            'total_utang' => $penjualans->sum('utang'),
            'total_penebusan' => $penebusans->sum('nominal'),
        ];

        $branches = $isSuperAdmin ? Branch::all() : collect();

        return view('report.global', compact(
            'allData',
            'penjualans',
            'expenses',
            'returs',
            'summary',
            'startDate',
            'endDate',
            'branches',
            'isSuperAdmin',
            'selectedBranchId'
        ));
    }

    /**
     * Helper to apply common filters
     */
    private function applyFilters($query, $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penjualan', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
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
