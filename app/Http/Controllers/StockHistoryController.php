<?php

namespace App\Http\Controllers;

use App\Models\StockMutation;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $isSuperAdmin = $user->hasRole('superadmin');
        $branchId = $user->branch_id;

        $warehouse = \Illuminate\Support\Facades\DB::table('stock_histories')
            ->when(!$isSuperAdmin, fn($q) => $q->where('branch_id', $branchId))
            ->select(
                'tanggal',
                \Illuminate\Support\Facades\DB::raw("CASE WHEN jenis_transaksi = 'surat_jalan' THEN 'distribusi_ke_truk' ELSE jenis_transaksi END as jenis_mutasi"),
                'referensi',
                \Illuminate\Support\Facades\DB::raw("CASE WHEN stok_masuk > 0 THEN 'Luar' ELSE 'Gudang Utama' END as lokasi_asal"),
                \Illuminate\Support\Facades\DB::raw("CASE WHEN stok_masuk > 0 THEN 'Gudang Utama' ELSE 'Armada' END as lokasi_tujuan"),
                'stok_masuk',
                'stok_keluar',
                'stok_akhir',
                \Illuminate\Support\Facades\DB::raw("NULL as user_id"),
                'created_at'
            );

        $vehicle = \Illuminate\Support\Facades\DB::table('vehicle_stock_histories')
            ->join('trucks', 'vehicle_stock_histories.truck_id', '=', 'trucks.id')
            ->when(!$isSuperAdmin, fn($q) => $q->where('vehicle_stock_histories.branch_id', $branchId))
            ->select(
                'vehicle_stock_histories.tanggal',
                'jenis_mutasi',
                'referensi',
                \Illuminate\Support\Facades\DB::raw("CASE WHEN stok_masuk > 0 THEN 'Luar/Gudang' ELSE trucks.nomor_polisi END as lokasi_asal"),
                \Illuminate\Support\Facades\DB::raw("CASE WHEN stok_masuk > 0 THEN trucks.nomor_polisi ELSE 'Luar' END as lokasi_tujuan"),
                'stok_masuk',
                'stok_keluar',
                'stok_akhir',
                \Illuminate\Support\Facades\DB::raw("NULL as user_id"),
                'vehicle_stock_histories.created_at'
            );

        $mutation = \Illuminate\Support\Facades\DB::table('stock_mutations')
            ->when(!$isSuperAdmin, fn($q) => $q->where('branch_id', $branchId))
            ->select(
                'tanggal',
                'jenis_mutasi',
                'referensi',
                'lokasi_asal',
                'lokasi_tujuan',
                'stok_masuk',
                'stok_keluar',
                'stok_akhir',
                'user_id',
                'created_at'
            );

        $query = $warehouse->union($vehicle)->union($mutation);

        // Apply filters
        if ($request->filled('jenis_mutasi') || ($request->filled('start_date') && $request->filled('end_date'))) {
            $query = \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw("({$query->toSql()}) as combined"))
                ->mergeBindings($query);
            
            if ($request->filled('jenis_mutasi')) {
                $query->where('jenis_mutasi', $request->jenis_mutasi);
            }
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
        }

        $mutations = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('stock-history.index', compact('mutations'));
    }
}
