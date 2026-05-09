<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranPiutangRequest;
use App\Models\PembayaranPiutang;
use App\Models\Piutang;
use Illuminate\Http\Request;
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
            
            if ($piutang->status_piutang === 'lunas' || $piutang->sisa_tagihan <= 0) {
                throw new \Exception('Piutang ini sudah lunas.');
            }

            if ($data['nominal_pembayaran'] > $piutang->sisa_tagihan) {
                throw new \Exception('Nominal pembayaran melebihi sisa tagihan (Sisa: Rp ' . number_format($piutang->sisa_tagihan, 0, ',', '.') . ').');
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

    public function pelunasan(Request $request, Piutang $piutang)
    {
        $data = $request->validate([
            'tanggal_pembayaran' => 'nullable|date',
            'metode_pembayaran' => 'nullable|in:cash,transfer',
        ]);

        DB::beginTransaction();
        try {
            $piutang->refresh();

            if ($piutang->status_piutang === 'lunas' || $piutang->sisa_tagihan <= 0) {
                throw new \Exception('Piutang ini sudah lunas.');
            }

            PembayaranPiutang::create([
                'piutang_id' => $piutang->id,
                'tanggal_pembayaran' => $data['tanggal_pembayaran'] ?? now()->toDateString(),
                'nominal_pembayaran' => $piutang->sisa_tagihan,
                'metode_pembayaran' => $data['metode_pembayaran'] ?? 'cash',
                'keterangan' => 'Pelunasan piutang',
                'user_id' => Auth::id(),
            ]);

            $piutang->updateCalculations();

            DB::commit();
            return back()->with('success', 'Piutang berhasil dilunasi. Riwayat transaksi utang tetap tersimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal melunasi piutang: ' . $e->getMessage());
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
