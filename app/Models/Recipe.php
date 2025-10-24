<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'delivery_price' => 'decimal:2',
        ];
    }

    protected $fillable = [
        'name',
        'description',
        'cooking_time',
        'difficulty',
        'servings',
        'category',
        'image_url',
        'delivery_price',
        'instructions',
    ];

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot('quantity', 'is_optional', 'notes')
            ->withTimestamps();
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function calculateCost(?int $servings = null): float
    {
        $servings = $servings ?? $this->servings;
        $multiplier = $servings / $this->servings;

        return $this->ingredients->sum(function ($ingredient) use ($multiplier) {
            return $ingredient->pivot->quantity * $multiplier * $ingredient->current_price;
        });
    }

    public function calculateCostPerServing(?int $servings = null): float
    {
        $servings = $servings ?? $this->servings;
        $totalCost = $this->calculateCost($servings);

        return $totalCost / $servings;
    }

    public function calculateSavings(?int $servings = null): float
    {
        if (! $this->delivery_price) {
            return 0;
        }

        $servings = $servings ?? $this->servings;
        $cookingCost = $this->calculateCost($servings);

        return $this->delivery_price - $cookingCost;
    }

    public function calculateSavingsPercentage(?int $servings = null): float
    {
        if (! $this->delivery_price || $this->delivery_price == 0) {
            return 0;
        }

        $savings = $this->calculateSavings($servings);

        return ($savings / $this->delivery_price) * 100;
    }
}
