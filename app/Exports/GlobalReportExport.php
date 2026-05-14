<?php

namespace App\Exports;

use App\Exports\GlobalReport\CoverSheet;
use App\Exports\GlobalReport\RangkumanPenjualanSheet;
use App\Exports\GlobalReport\RekapPenjualanHarianSheet;
use App\Exports\GlobalReport\RekapPengeluaranHarianSheet;
use App\Exports\GlobalReport\RekapPembelianRefillSheet;
use App\Exports\GlobalReport\LabaRugiSheet;
use App\Exports\GlobalReport\DokumenAlokasiSheet;
use App\Models\Penjualan;
use App\Models\Expense;
use App\Models\Penebusan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GlobalReportExport implements WithMultipleSheets
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        $startDate = ($this->request->start_date && $this->request->start_date !== '') 
            ? $this->request->start_date 
            : Carbon::now()->startOfMonth()->toDateString();
            
        $endDate = ($this->request->end_date && $this->request->end_date !== '') 
            ? $this->request->end_date 
            : Carbon::now()->endOfMonth()->toDateString();
        
        $user = Auth::user();
        $isSuperAdmin = $user ? $user->hasRole('superadmin') : true;
        $branchId = $user ? $user->branch_id : null;
        
        // Use branch_id from request if superadmin, otherwise use user's branch
        $selectedBranchId = $this->request->branch_id;
        if (!$isSuperAdmin) {
            $selectedBranchId = $branchId;
        }

        $period = Carbon::parse($startDate)->translatedFormat('F Y');

        $penjualans = Penjualan::with(['pangkalan', 'branch'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereBetween('tanggal_penjualan', [$startDate, $endDate])
            ->get();

        $expenses = Expense::with(['category', 'branch'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->where('status_verifikasi', 'disetujui')
            ->whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->get();

        $penebusans = Penebusan::with(['branch'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereBetween('tanggal_penebusan', [$startDate, $endDate])
            ->get();

        $totalPenebusanQty = $penebusans->sum('jumlah_tabung');
        $totalPenebusanNominal = $penebusans->sum('total_penebusan');
        $avgCost = $totalPenebusanQty > 0 ? $totalPenebusanNominal / $totalPenebusanQty : 15500;

        $totals = [
            'total_penjualan' => $penjualans->sum('total_penjualan'),
            'total_tabung_penjualan' => $penjualans->sum('jumlah_tabung'),
            'total_expenses' => $expenses->sum('nominal'),
            'total_penebusan' => $totalPenebusanNominal,
        ];

        return [
            new CoverSheet($period),
            new RangkumanPenjualanSheet($penjualans, $avgCost, $period),
            new RekapPenjualanHarianSheet($penjualans),
            new RekapPengeluaranHarianSheet($expenses),
            new RekapPembelianRefillSheet($penebusans),
            new LabaRugiSheet($totals, $period),
            new DokumenAlokasiSheet($period),
        ];
    }
}
