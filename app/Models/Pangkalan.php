<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pangkalan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_pangkalan',
        'nama_pemilik',
        'alamat',
        'no_hp',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public function penjualans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Penjualan::class);
    }

    public function piutangs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Piutang::class);
    }
}
