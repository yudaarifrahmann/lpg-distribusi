<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use App\Models\Pangkalan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $query = Piutang::with(['pangkalan', 'penjualan'])
            ->withCount(['pembayarans as pending_count' => function($q) {
                $q->where('status_verifikasi', 'pending');
            }])
            ->addSelect(['initial_pending' => Penjualan::selectRaw('count(*)')
                ->whereColumn('penjualans.id', 'piutangs.penjualan_id')
                ->where('status_transfer', 'pending')
                ->limit(1)
            ]);

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
