<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use BelongsToBranch;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'truck_id',
        'nama',
        'nomor_hp',
        'alamat',
        'role_pekerjaan',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function suratJalansSupir(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SuratJalan::class, 'driver_id');
    }

    public function suratJalansKnek(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SuratJalan::class, 'knek_id');
    }
}
