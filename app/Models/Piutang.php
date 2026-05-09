<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Piutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_piutang',
        'tanggal_piutang',
        'penjualan_id',
        'pangkalan_id',
        'nominal_piutang',
        'total_terbayar',
        'sisa_tagihan',
        'tanggal_jatuh_tempo',
        'status_piutang',
        'catatan',
    ];

    protected $casts = [
        'tanggal_piutang' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'nominal_piutang' => 'decimal:2',
        'total_terbayar' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function pangkalan(): BelongsTo
    {
        return $this->belongsTo(Pangkalan::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(PembayaranPiutang::class);
    }

    /**
     * Update sisa tagihan and status automatically
     */
    public function updateCalculations()
    {
        $this->total_terbayar = $this->pembayarans()
            ->where('status_verifikasi', 'verified')
            ->sum('nominal_pembayaran');
        $this->sisa_tagihan = $this->nominal_piutang - $this->total_terbayar;

        if ($this->total_terbayar >= $this->nominal_piutang) {
            $this->status_piutang = 'lunas';
            $this->sisa_tagihan = 0;
            
            // Also update parent penjualan status if needed
            $this->penjualan->update(['status_pembayaran' => 'lunas']);
        } elseif ($this->total_terbayar > 0) {
            $this->status_piutang = 'mencicil';
            $this->penjualan->update(['status_pembayaran' => 'cicilan']);
        } else {
            $this->status_piutang = 'belum_bayar';
            $this->penjualan->update(['status_pembayaran' => 'belum_lunas']);
        }

        $this->save();
    }
}
