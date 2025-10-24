<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    /** @use HasFactory<\Database\Factories\IngredientFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'current_price' => 'decimal:2',
            'price_updated_at' => 'datetime',
        ];
    }

    protected $fillable = [
        'name',
        'category',
        'unit',
        'current_price',
        'price_updated_at',
        'description',
        'image_url',
    ];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
            ->withPivot('quantity', 'is_optional', 'notes')
            ->withTimestamps();
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function updatePrice(float $newPrice, ?string $source = null): void
    {
        $this->priceHistories()->create([
            'price' => $this->current_price,
            'source' => $source,
            'recorded_at' => now(),
        ]);

        $this->update([
            'current_price' => $newPrice,
            'price_updated_at' => now(),
        ]);
    }
}
