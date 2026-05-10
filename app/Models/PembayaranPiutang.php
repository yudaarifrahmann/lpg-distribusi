<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPiutang extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'piutang_id',
        'tanggal_pembayaran',
        'nominal_pembayaran',
        'metode_pembayaran',
        'status_verifikasi',
        'bukti_pembayaran',
        'keterangan',
        'user_id',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'nominal_pembayaran' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function piutang(): BelongsTo
    {
        return $this->belongsTo(Piutang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
