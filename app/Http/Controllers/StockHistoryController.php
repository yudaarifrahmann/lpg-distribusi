<?php

namespace App\Http\Controllers;

use App\Models\StockMutation;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMutation::with('user');

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $mutations = $query->latest('tanggal')->paginate(20);

        return view('stock-history.index', compact('mutations'));
    }
}
