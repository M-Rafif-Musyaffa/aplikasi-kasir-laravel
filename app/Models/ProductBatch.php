<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'harga_beli',
        'stok_awal',
        'stok_sisa',
        'tgl_masuk',
        'tgl_expired',
    ];

    /**
     * Mendefinisikan bahwa satu Batch MILIK satu Produk
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
