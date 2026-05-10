<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class StockHistory extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jenis_transaksi',
        'referensi',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
