<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tanggal_pengeluaran',
        'user_id',
        'expense_category_id',
        'nama_pengeluaran',
        'nominal',
        'metode_pembayaran',
        'keterangan',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
        'nominal' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ExpenseAttachment::class);
    }
}
