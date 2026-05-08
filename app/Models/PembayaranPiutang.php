<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPiutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'piutang_id',
        'tanggal_pembayaran',
        'nominal_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'nominal_pembayaran' => 'decimal:2',
    ];

    public function piutang(): BelongsTo
    {
        return $this->belongsTo(Piutang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
