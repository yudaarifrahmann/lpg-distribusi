<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'nomor_invoice',
        'tanggal_penjualan',
        'surat_jalan_id',
        'truck_id',
        'driver_id',
        'pangkalan_id',
        'lpg_price_id',
        'jumlah_tabung',
        'harga_satuan',
        'total_penjualan',
        'nominal_cash',
        'nominal_transfer',
        'metode_pembayaran',
        'status_pembayaran',
        'status_transfer',
        'catatan',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'total_penjualan'   => 'decimal:2',
        'harga_satuan'      => 'decimal:2',
        'nominal_cash'      => 'decimal:2',
        'nominal_transfer'  => 'decimal:2',
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

    public function pangkalan(): BelongsTo
    {
        return $this->belongsTo(Pangkalan::class);
    }

    public function lpgPrice(): BelongsTo
    {
        return $this->belongsTo(LpgPrice::class);
    }

    public function piutang(): HasOne
    {
        return $this->hasOne(Piutang::class);
    }

    public function returs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReturTabung::class);
    }
}
