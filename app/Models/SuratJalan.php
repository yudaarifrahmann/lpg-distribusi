<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratJalan extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'nomor_surat_jalan',
        'tanggal_berangkat',
        'penebusan_id',
        'truck_id',
        'driver_id',
        'knek_id',
        'jumlah_tabung',
        'status_perjalanan',
        'catatan',
        'foto_surat_jalan',
        'muat_dari_gudang',
        'is_supir_tembak',
        'nama_supir_tembak',
        'alamat_supir_tembak',
        'no_hp_supir_tembak',
        'is_knek_tembak',
        'nama_knek_tembak',
        'alamat_knek_tembak',
        'no_hp_knek_tembak',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'muat_dari_gudang' => 'boolean',
        'is_supir_tembak' => 'boolean',
        'is_knek_tembak' => 'boolean',
    ];

    public function penebusan(): BelongsTo
    {
        return $this->belongsTo(Penebusan::class);
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function supir(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function knek(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'knek_id');
    }

    public function penjualans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Penjualan::class);
    }
}
