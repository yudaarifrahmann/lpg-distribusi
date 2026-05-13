<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToBranch;

class HistoriMutasiTitipan extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'histori_mutasi_titipan';

    protected $fillable = [
        'tanggal',
        'jenis_mutasi',
        'pemilik_tabung',
        'peminjam',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'user_input',
        'branch_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_input');
    }
}
