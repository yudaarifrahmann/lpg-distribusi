<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use App\Models\StockSummary;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $summary = StockSummary::first();
        
        if (!$summary) {
            $summary = StockSummary::create(['stok_saat_ini' => 0]);
        }
        
        $query = StockHistory::query();

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_transaksi', $request->jenis);
        }

        $histories = $query->latest()->paginate(15);

        return view('stock.index', compact('summary', 'histories'));
    }
}
