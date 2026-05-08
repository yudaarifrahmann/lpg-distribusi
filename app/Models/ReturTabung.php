<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturTabung extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_retur',
        'surat_jalan_id',
        'truck_id',
        'driver_id',
        'jumlah_retur',
        'kondisi_tabung',
        'keterangan',
        'status_retur',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_retur' => 'date',
        'verified_at' => 'datetime',
    ];

    public function suratJalan(): BelongsTo
    {
        return $this->belongsTo(SuratJalan::class);
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function supir(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
