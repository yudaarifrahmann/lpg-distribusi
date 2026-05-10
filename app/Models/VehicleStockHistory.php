<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleStockHistory extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'tanggal',
        'truck_id',
        'jenis_mutasi',
        'referensi',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }
}
