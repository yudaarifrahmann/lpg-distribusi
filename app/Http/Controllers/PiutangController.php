<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use App\Models\Pangkalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $query = Piutang::with(['pangkalan', 'penjualan']);

        // Filtering
        if ($request->filled('pangkalan_id')) {
            $query->where('pangkalan_id', $request->pangkalan_id);
        }

        if ($request->filled('status')) {
            $query->where('status_piutang', $request->status);
        }

        $piutangs = $query->latest()->paginate(15);
        $pangkalans = Pangkalan::where('status', 'aktif')->get();

        return view('piutang.index', compact('piutangs', 'pangkalans'));
    }

    public function show(Piutang $piutang)
    {
        $piutang->load(['pangkalan', 'penjualan.suratJalan.truck', 'penjualan.supir', 'pembayarans.user']);
        return view('piutang.show', compact('piutang'));
    }
}
