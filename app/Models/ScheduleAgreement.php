<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_sa',
        'jumlah_do',
        'jumlah_tabung',
        'keterangan',
        'status_sa',
    ];

    protected $casts = [
        'tanggal_sa' => 'date',
    ];

    public function penebusans(): HasMany
    {
        return $this->hasMany(Penebusan::class);
    }
}
