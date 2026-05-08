<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jenis_mutasi',
        'referensi',
        'lokasi_asal',
        'lokasi_tujuan',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
