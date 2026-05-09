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

            $payments = $this->normalizePayments($request, $data);
            $verifiedTotal = collect($payments)
                ->where('status_verifikasi', 'verified')
                ->sum('nominal_pembayaran');

            if ($verifiedTotal > $piutang->sisa_tagihan) {
                throw new \Exception('Total pembayaran cash/terverifikasi melebihi sisa tagihan (Sisa: Rp ' . number_format($piutang->sisa_tagihan, 0, ',', '.') . ').');
            }

            if (empty($payments)) {
                throw new \Exception('Pilih minimal satu pembayaran dan isi nominalnya.');
            }

            foreach ($payments as $payment) {
                PembayaranPiutang::create($payment);
            }
            
            // Trigger recalculation in model
            $piutang->updateCalculations();

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dicatat. Transfer akan mengurangi utang setelah diverifikasi.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    private function normalizePayments(StorePembayaranPiutangRequest $request, array $data): array
    {
        if (!empty($data['payments'])) {
            $payments = [];

            foreach ($data['payments'] as $index => $payment) {
                if (empty($payment['aktif']) || empty($payment['nominal_pembayaran']) || empty($payment['metode_pembayaran'])) {
                    continue;
                }

                $status = $payment['metode_pembayaran'] === 'transfer' ? 'pending' : 'verified';
                $bukti = null;

                if ($request->hasFile("payments.$index.bukti_pembayaran")) {
                    $bukti = $request->file("payments.$index.bukti_pembayaran")->store('pembayaran-piutang', 'public');
                }

                $payments[] = [
                    'piutang_id' => $data['piutang_id'],
                    'tanggal_pembayaran' => $data['tanggal_pembayaran'],
                    'nominal_pembayaran' => $payment['nominal_pembayaran'],
                    'metode_pembayaran' => $payment['metode_pembayaran'],
                    'status_verifikasi' => $status,
                    'bukti_pembayaran' => $bukti,
                    'keterangan' => $payment['keterangan'] ?? ($status === 'pending' ? 'Transfer menunggu verifikasi' : 'Pembayaran cash'),
                    'user_id' => Auth::id(),
                    'verified_by' => $status === 'verified' ? Auth::id() : null,
                    'verified_at' => $status === 'verified' ? now() : null,
                ];
            }

            return $payments;
        }

        $status = $data['metode_pembayaran'] === 'transfer' ? 'pending' : 'verified';

        if ($request->hasFile('bukti_pembayaran')) {
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pembayaran-piutang', 'public');
        }

        return [[
            'piutang_id' => $data['piutang_id'],
            'tanggal_pembayaran' => $data['tanggal_pembayaran'],
            'nominal_pembayaran' => $data['nominal_pembayaran'],
            'metode_pembayaran' => $data['metode_pembayaran'],
            'status_verifikasi' => $status,
            'bukti_pembayaran' => $data['bukti_pembayaran'] ?? null,
            'keterangan' => $data['keterangan'] ?? ($status === 'pending' ? 'Transfer menunggu verifikasi' : 'Pembayaran cash'),
            'user_id' => Auth::id(),
            'verified_by' => $status === 'verified' ? Auth::id() : null,
            'verified_at' => $status === 'verified' ? now() : null,
        ]];
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
                'status_verifikasi' => ($data['metode_pembayaran'] ?? 'cash') === 'transfer' ? 'pending' : 'verified',
                'keterangan' => 'Pelunasan piutang',
                'user_id' => Auth::id(),
                'verified_by' => ($data['metode_pembayaran'] ?? 'cash') === 'transfer' ? null : Auth::id(),
                'verified_at' => ($data['metode_pembayaran'] ?? 'cash') === 'transfer' ? null : now(),
            ]);

            $piutang->updateCalculations();

            DB::commit();
            return back()->with('success', 'Piutang berhasil dilunasi. Riwayat transaksi utang tetap tersimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal melunasi piutang: ' . $e->getMessage());
        }
    }

    public function verify(PembayaranPiutang $pembayaran)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin_keuangan'])) {
            return back()->with('error', 'Hanya Admin Keuangan atau SuperAdmin yang dapat memverifikasi transfer.');
        }

        DB::beginTransaction();
        try {
            if ($pembayaran->status_verifikasi === 'verified') {
                throw new \Exception('Pembayaran ini sudah diverifikasi.');
            }

            $piutang = $pembayaran->piutang;

            if ($pembayaran->nominal_pembayaran > $piutang->sisa_tagihan) {
                throw new \Exception('Nominal transfer melebihi sisa tagihan saat ini.');
            }

            $pembayaran->update([
                'status_verifikasi' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $piutang->updateCalculations();

            DB::commit();
            return back()->with('success', 'Transfer berhasil diverifikasi dan masuk ke pembayaran piutang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi pembayaran: ' . $e->getMessage());
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
