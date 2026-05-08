<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'aktivitas',
        'module',
        'data_sebelum',
        'data_sesudah',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'data_sebelum' => 'json',
        'data_sesudah' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
