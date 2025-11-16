<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
   use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Definisikan relasi: Satu Kategori memiliki BANYAK Produk.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
