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
            
            // Base queries for conditional filtering
            $salesChartQuery = Penjualan::select(DB::raw('DATE(tanggal_penjualan) as date'), DB::raw('SUM(total_penjualan) as total'))
                ->whereBetween('tanggal_penjualan', [Carbon::now()->subDays(14), Carbon::now()]);
                
            $topPangkalanQuery = Penjualan::select('pangkalans.nama_pangkalan', DB::raw('SUM(penjualans.jumlah_tabung) as total'))
                ->join('pangkalans', 'penjualans.pangkalan_id', '=', 'pangkalans.id')
                ->whereBetween('penjualans.tanggal_penjualan', [$thisMonthStart, $thisMonthEnd]);
                
            $monthlyPenjualanQuery = Penjualan::whereBetween('tanggal_penjualan', [$thisMonthStart, $thisMonthEnd]);

            $stokGudang = StockSummary::first()?->stok_saat_ini ?? 0;
            $stokKendaraan = VehicleStock::sum('stok_saat_ini');
            $penebusanGlobal = Penebusan::whereBetween('tanggal_penebusan', [$thisMonthStart, $thisMonthEnd])->sum('total_penebusan');
            $pengeluaranGlobal = Expense::where('status_verifikasi', 'disetujui')->whereBetween('tanggal_pengeluaran', [$thisMonthStart, $thisMonthEnd])->sum('nominal');

            $stokGudang = StockSummary::first()?->stok_saat_ini ?? 0;
            $stokKendaraan = VehicleStock::sum('stok_saat_ini');
            $penebusanGlobal = Penebusan::whereBetween('tanggal_penebusan', [$thisMonthStart, $thisMonthEnd])->sum('total_penebusan');
            $pengeluaranGlobal = Expense::where('status_verifikasi', 'disetujui')->whereBetween('tanggal_pengeluaran', [$thisMonthStart, $thisMonthEnd])->sum('nominal');

            if (Auth::user()->hasRole('supir_knek')) {
                $driver = Auth::user()->driver;
                $driverId = $driver ? $driver->id : -1;
                
                $salesChartQuery->where('driver_id', $driverId);
                $topPangkalanQuery->where('penjualans.driver_id', $driverId);
                $monthlyPenjualanQuery->where('driver_id', $driverId);
                
                // For supir, find their truck via the latest assignment (Surat Jalan or Penebusan)
                if ($driver) {
                    $latestSJ = SuratJalan::where(function($q) use ($driverId) {
                        $q->where('driver_id', $driverId)
                          ->orWhere('knek_id', $driverId);
                    })->latest('created_at')->first();

                    $latestPenebusan = Penebusan::where('driver_id', $driverId)
                                                ->latest('created_at')->first();

                    $truckId = null;
                    if ($latestSJ && $latestPenebusan) {
                        if ($latestSJ->created_at->gt($latestPenebusan->created_at)) {
                            $truckId = $latestSJ->truck_id;
                        } else {
                            $truckId = $latestPenebusan->truck_id;
                        }
                    } elseif ($latestSJ) {
                        $truckId = $latestSJ->truck_id;
                    } elseif ($latestPenebusan) {
                        $truckId = $latestPenebusan->truck_id;
                    } else {
                        $truckId = $driver->truck_id;
                    }

                    if ($truckId) {
                        $stokKendaraan = VehicleStock::where('truck_id', $truckId)->sum('stok_saat_ini');
                    } else {
                        $stokKendaraan = 0;
                    }
                } else {
                    $stokKendaraan = 0;
                }
                
                // For supir, redemption and expenses are NOT their business
                $penebusanGlobal = 0;
                $pengeluaranGlobal = 0;
            }

            return [
                'countPangkalan' => Pangkalan::count(),
                'countTruck' => Truck::count(),
                'countDriver' => Driver::count(),
                'stokSaatIni' => $stokGudang,
                'stokKendaraanTotal' => $stokKendaraan,
                'totalReturHariIni' => ReturTabung::whereDate('tanggal_retur', $today)->where('status_retur', 'diterima')->sum('jumlah_retur'),
                'totalTabungRusak' => ReturTabung::where('kondisi_tabung', 'rusak')->where('status_retur', 'diterima')->sum('jumlah_retur'),
                'totalMutasiHariIni' => StockMutation::whereDate('tanggal', $today)->count(),
                'salesChart' => $salesChartQuery->groupBy('date')->orderBy('date')->get(),
                'topPangkalan' => $topPangkalanQuery->groupBy('pangkalans.nama_pangkalan')->orderByDesc('total')->limit(5)->get(),
                'monthlyLaba' => [
                    'penjualan' => $monthlyPenjualanQuery->sum('total_penjualan'),
                    'penebusan' => $penebusanGlobal,
                    'pengeluaran' => $pengeluaranGlobal,
                ]
            ];
        });

        // Real-time stats
        $penjualanTodayQuery = Penjualan::whereDate('tanggal_penjualan', $today);
        $piutangQuery = Piutang::where('status_piutang', '!=', 'lunas');
        $belumLunasQuery = Piutang::where('status_piutang', 'belum_bayar');
        $cicilanQuery = Piutang::where('status_piutang', 'mencicil');

        if (Auth::user()->hasRole('supir_knek')) {
            $driver = Auth::user()->driver;
            $driverId = $driver ? $driver->id : 0;
            
            $penjualanTodayQuery->where('driver_id', $driverId);
            
            $piutangQuery->whereHas('penjualan', function($q) use ($driverId) {
                $q->where('driver_id', $driverId);
            });
            $belumLunasQuery->whereHas('penjualan', function($q) use ($driverId) {
                $q->where('driver_id', $driverId);
            });
            $cicilanQuery->whereHas('penjualan', function($q) use ($driverId) {
                $q->where('driver_id', $driverId);
            });
        }

        $totalPenjualanHariIni = $penjualanTodayQuery->sum('total_penjualan');
        $totalPiutangAktif = $piutangQuery->sum('sisa_tagihan');
        $totalBelumLunas = $belumLunasQuery->count();
        $totalCicilan = $cicilanQuery->count();

        // Piutang Jatuh Tempo
        $overduePiutangsQuery = Piutang::with('pangkalan')
            ->where('status_piutang', '!=', 'lunas')
            ->whereDate('tanggal_jatuh_tempo', '<=', $today);

        if (Auth::user()->hasRole('supir_knek')) {
            $driver = Auth::user()->driver;
            $driverId = $driver ? $driver->id : 0;
            $overduePiutangsQuery->whereHas('penjualan', function($q) use ($driverId) {
                $q->where('driver_id', $driverId);
            });
        }

        $overduePiutangs = $overduePiutangsQuery->orderBy('tanggal_jatuh_tempo', 'asc')->get();

        // Merge cached and real-time data
        $data = array_merge($stats, [
            'totalPenjualanHariIni' => $totalPenjualanHariIni,
            'totalPiutangAktif' => $totalPiutangAktif,
            'totalBelumLunas' => $totalBelumLunas,
            'totalCicilan' => $totalCicilan,
            'overduePiutangs' => $overduePiutangs,
        ]);
        
        $data['monthlyLaba']['net'] = $data['monthlyLaba']['penjualan'] - $data['monthlyLaba']['penebusan'] - $data['monthlyLaba']['pengeluaran'];

        return view('dashboard.index', $data);
    }
}
