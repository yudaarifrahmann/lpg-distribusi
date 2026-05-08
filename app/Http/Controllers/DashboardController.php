<?php

namespace App\Http\Controllers;

use App\Models\Pangkalan;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\Penebusan;
use App\Models\SuratJalan;
use App\Models\Penjualan;
use App\Models\Piutang;
use App\Models\PembayaranPiutang;
use App\Models\Expense;
use App\Models\ReturTabung;
use App\Models\StockSummary;
use App\Models\StockMutation;
use App\Models\VehicleStock;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();
        
        $cacheKey = 'dashboard_stats_' . Auth::id();
        $stats = Cache::remember($cacheKey, 300, function() use ($today, $thisMonthStart, $thisMonthEnd) {
            $driver = Auth::user()->hasRole('supir_knek') ? Auth::user()->driver : null;
            
            return [
                'countPangkalan' => Pangkalan::count(),
                'countTruck' => Truck::count(),
                'countDriver' => Driver::count(),
                'stokSaatIni' => StockSummary::first()?->stok_saat_ini ?? 0,
                'stokKendaraanTotal' => VehicleStock::sum('stok_saat_ini'),
                'totalReturHariIni' => ReturTabung::whereDate('tanggal_retur', $today)->where('status_retur', 'diterima')->sum('jumlah_retur'),
                'totalTabungRusak' => ReturTabung::where('kondisi_tabung', 'rusak')->where('status_retur', 'diterima')->sum('jumlah_retur'),
                'totalMutasiHariIni' => StockMutation::whereDate('tanggal', $today)->count(),
                'salesChart' => Penjualan::select(DB::raw('DATE(tanggal_penjualan) as date'), DB::raw('SUM(total_penjualan) as total'))
                    ->whereBetween('tanggal_penjualan', [Carbon::now()->subDays(14), Carbon::now()])
                    ->groupBy('date')->orderBy('date')->get(),
                'topPangkalan' => Penjualan::select('pangkalans.nama_pangkalan', DB::raw('SUM(penjualans.jumlah_tabung) as total'))
                    ->join('pangkalans', 'penjualans.pangkalan_id', '=', 'pangkalans.id')
                    ->whereBetween('penjualans.tanggal_penjualan', [$thisMonthStart, $thisMonthEnd])
                    ->groupBy('pangkalans.nama_pangkalan')->orderByDesc('total')->limit(5)->get(),
                'monthlyLaba' => [
                    'penjualan' => Penjualan::whereBetween('tanggal_penjualan', [$thisMonthStart, $thisMonthEnd])->sum('total_penjualan'),
                    'penebusan' => Penebusan::whereBetween('tanggal_penebusan', [$thisMonthStart, $thisMonthEnd])->sum('total_penebusan'),
                    'pengeluaran' => Expense::where('status_verifikasi', 'disetujui')->whereBetween('tanggal_pengeluaran', [$thisMonthStart, $thisMonthEnd])->sum('nominal'),
                ]
            ];
        });

        // Real-time stats (not cached as they change frequently and are cheap)
        $penjualanTodayQuery = Penjualan::whereDate('tanggal_penjualan', $today);
        if (Auth::user()->hasRole('supir_knek')) {
            $driver = Auth::user()->driver;
            $penjualanTodayQuery->where('driver_id', $driver ? $driver->id : 0);
        }
        $totalPenjualanHariIni = $penjualanTodayQuery->sum('total_penjualan');
        $totalPiutangAktif = Piutang::where('status_piutang', '!=', 'lunas')->sum('sisa_tagihan');
        $totalBelumLunas = Piutang::where('status_piutang', 'belum_bayar')->count();
        $totalCicilan = Piutang::where('status_piutang', 'mencicil')->count();

        // Merge cached and real-time data
        $data = array_merge($stats, [
            'totalPenjualanHariIni' => $totalPenjualanHariIni,
            'totalPiutangAktif' => $totalPiutangAktif,
            'totalBelumLunas' => $totalBelumLunas,
            'totalCicilan' => $totalCicilan,
        ]);
        
        $data['monthlyLaba']['net'] = $data['monthlyLaba']['penjualan'] - $data['monthlyLaba']['penebusan'] - $data['monthlyLaba']['pengeluaran'];

        return view('dashboard.index', $data);
    }
}
