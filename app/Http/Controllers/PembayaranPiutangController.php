<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranPiutangRequest;
use App\Models\PembayaranPiutang;
use App\Models\Piutang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranPiutangController extends Controller
{
    public function store(StorePembayaranPiutangRequest $request)
    {
        $data = $request->validated();
        
        DB::beginTransaction();
        try {
            $piutang = Piutang::findOrFail($data['piutang_id']);
            
            // Check if payment exceeds sisa tagihan
            if ($data['nominal_pembayaran'] > $piutang->sisa_tagihan) {
                // We allow overpayment? Usually not.
                // throw new \Exception('Nominal pembayaran melebihi sisa tagihan (Sisa: ' . number_format($piutang->sisa_tagihan) . ')');
            }

            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('pembayaran-piutang', 'public');
                $data['bukti_pembayaran'] = $path;
            }

            $data['user_id'] = Auth::id();
            
            $pembayaran = PembayaranPiutang::create($data);
            
            // Trigger recalculation in model
            $piutang->updateCalculations();

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dicatat.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function destroy(PembayaranPiutang $pembayaran)
    {
        if (!Auth::user()->hasRole('superadmin')) {
            return back()->with('error', 'Hanya SuperAdmin yang dapat menghapus riwayat pembayaran.');
        }

        DB::beginTransaction();
        try {
            $piutang = $pembayaran->piutang;
            
            if ($pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            }
            
            $pembayaran->delete();
            $piutang->updateCalculations();

            DB::commit();
            return back()->with('success', 'Riwayat pembayaran berhasil dihapus dan tagihan diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
