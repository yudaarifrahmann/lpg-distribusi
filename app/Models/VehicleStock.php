<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleStock extends Model
{
    use HasFactory;

    protected $fillable = ['truck_id', 'stok_saat_ini'];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }
}
