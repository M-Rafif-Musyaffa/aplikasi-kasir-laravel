<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
    ];

    /**
     * Definisikan relasi: Satu Tarif Pajak memiliki BANYAK Produk.
     * (INI ADALAH FUNGSI YANG HILANG)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
