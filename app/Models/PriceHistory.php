<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'recorded_at' => 'datetime',
        ];
    }

    protected $fillable = [
        'ingredient_id',
        'price',
        'source',
        'recorded_at',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
