<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'path_file',
        'nama_file',
        'mime_type',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
