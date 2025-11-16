<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapitalInjection extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi.
     */
    protected $fillable = [
        'date',
        'description',
        'amount',
        'user_id',
    ];

    /**
     * Relasi: Catatan modal ini dicatat oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
