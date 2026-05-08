<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_adjustment',
        'lokasi_stok',
        'truck_id',
        'stok_sebelum',
        'stok_setelah',
        'selisih',
        'alasan_penyesuaian',
        'user_id',
    ];

    protected $casts = [
        'tanggal_adjustment' => 'date',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
