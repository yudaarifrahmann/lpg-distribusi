<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class StockSummary extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = ['stok_saat_ini'];
}
