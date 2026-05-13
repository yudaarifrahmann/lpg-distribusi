<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class StokOperasional extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'stok_operasional';

    protected $fillable = [
        'lokasi_stok',
        'jumlah_tabung',
        'jenis_lokasi',
        'branch_id',
    ];
}
