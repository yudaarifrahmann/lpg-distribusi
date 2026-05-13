<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleAgreement extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'tanggal_sa',
        'driver_id',
        'truck_id',
        'jumlah_do',
        'jumlah_tabung',
        'keterangan',
        'status_sa',
        'branch_id',
    ];

    protected $casts = [
        'tanggal_sa' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function penebusans(): HasMany
    {
        return $this->hasMany(Penebusan::class);
    }
}
