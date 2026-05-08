<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpgPrice extends Model
{
    protected $table = 'lpg_prices';

    protected $fillable = [
        'nama_harga',
        'harga',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function penjualans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Penjualan::class);
    }
}
