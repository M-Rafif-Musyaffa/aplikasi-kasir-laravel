<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'harga_jual',
        'category_id',
        'tax_rate_id'
    ];
    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    /**
 * Definisikan relasi: Satu Produk MILIK SATU Tarif Pajak.
 */
public function taxRate(): BelongsTo
{
    return $this->belongsTo(TaxRate::class);
}
}
