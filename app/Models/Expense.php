<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi.
     */
    protected $fillable = [
        'expense_date',
        'description',
        'amount',
        'user_id',
        'expense_category_id',
    ];

    /**
     * Relasi: Biaya ini dicatat oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Biaya ini milik satu Kategori Biaya.
     * (INI ADALAH FUNGSI YANG HILANG DAN MENYEBABKAN ERROR)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
