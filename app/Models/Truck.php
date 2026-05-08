<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_truk',
        'nomor_polisi',
        'kapasitas_tabung',
        'status_kendaraan',
    ];

    protected $casts = [
        'kapasitas_tabung' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public function vehicleStock(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VehicleStock::class);
    }

    public function suratJalans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SuratJalan::class);
    }
}
