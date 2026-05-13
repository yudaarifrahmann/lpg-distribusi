<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class StokTitipan extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'stok_titipan';

    protected $fillable = [
        'pemilik_tabung',
        'jumlah_tersedia',
        'jumlah_dipinjam',
        'total_tabung',
        'branch_id',
    ];
}
