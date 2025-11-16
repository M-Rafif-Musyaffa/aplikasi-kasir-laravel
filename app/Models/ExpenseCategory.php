<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Definisikan relasi: Satu Kategori Biaya memiliki BANYAK Biaya.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
