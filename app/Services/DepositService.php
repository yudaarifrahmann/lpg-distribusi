<?php

namespace App\Services;

use App\Models\StokTitipan;
use App\Models\HistoriMutasiTitipan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DepositService
{
    /**
     * Catat Titip Tabung
     */
    public function deposit(array $data)
    {
        return DB::transaction(function () use ($data) {
            $stock = StokTitipan::firstOrCreate(
                ['pemilik_tabung' => $data['pemilik_tabung'], 'branch_id' => Auth::user()->branch_id],
                ['jumlah_tersedia' => 0, 'jumlah_dipinjam' => 0, 'total_tabung' => 0]
            );

            $stokSebelum = $stock->jumlah_tersedia;
            
            $stock->jumlah_tersedia += $data['jumlah'];
            $stock->total_tabung += $data['jumlah'];
            $stock->save();

            return HistoriMutasiTitipan::create([
                'tanggal' => $data['tanggal'] ?? now(),
                'jenis_mutasi' => 'titip',
                'pemilik_tabung' => $data['pemilik_tabung'],
                'jumlah' => $data['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stock->jumlah_tersedia,
                'user_input' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        });
    }

    /**
     * Catat Pinjam Tabung
     */
    public function loan(array $data)
    {
        return DB::transaction(function () use ($data) {
            $stock = StokTitipan::where('pemilik_tabung', $data['pemilik_tabung'])
                ->where('branch_id', Auth::user()->branch_id)
                ->firstOrFail();

            if ($stock->jumlah_tersedia < $data['jumlah']) {
                throw new \Exception("Stok titipan milik {$data['pemilik_tabung']} tidak mencukupi untuk dipinjam.");
            }

            $stokSebelum = $stock->jumlah_tersedia;

            $stock->jumlah_tersedia -= $data['jumlah'];
            $stock->jumlah_dipinjam += $data['jumlah'];
            $stock->save();

            return HistoriMutasiTitipan::create([
                'tanggal' => $data['tanggal'] ?? now(),
                'jenis_mutasi' => 'pinjam',
                'pemilik_tabung' => $data['pemilik_tabung'],
                'peminjam' => $data['peminjam'],
                'jumlah' => $data['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stock->jumlah_tersedia,
                'user_input' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        });
    }

    /**
     * Catat Pengembalian Tabung
     */
    public function return(array $data)
    {
        return DB::transaction(function () use ($data) {
            $stock = StokTitipan::where('pemilik_tabung', $data['pemilik_tabung'])
                ->where('branch_id', Auth::user()->branch_id)
                ->firstOrFail();

            if ($stock->jumlah_dipinjam < $data['jumlah']) {
                throw new \Exception("Jumlah pengembalian melebihi jumlah yang sedang dipinjam.");
            }

            $stokSebelum = $stock->jumlah_tersedia;

            $stock->jumlah_tersedia += $data['jumlah'];
            $stock->jumlah_dipinjam -= $data['jumlah'];
            $stock->save();

            return HistoriMutasiTitipan::create([
                'tanggal' => $data['tanggal'] ?? now(),
                'jenis_mutasi' => 'pengembalian',
                'pemilik_tabung' => $data['pemilik_tabung'],
                'peminjam' => $data['peminjam'] ?? null,
                'jumlah' => $data['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stock->jumlah_tersedia,
                'user_input' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        });
    }
}
