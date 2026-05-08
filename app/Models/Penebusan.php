<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penebusan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_do',
        'tanggal_penebusan',
        'schedule_agreement_id',
        'jumlah_tabung',
        'harga_per_do',
        'total_penebusan',
        'truck_id',
        'driver_id',
        'foto_nota',
        'status_penebusan',
    ];

    protected $casts = [
        'tanggal_penebusan' => 'date',
    ];

    public function scheduleAgreement(): BelongsTo
    {
        return $this->belongsTo(ScheduleAgreement::class);
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function suratJalan(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SuratJalan::class);
    }
}
