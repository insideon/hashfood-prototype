<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected function casts(): array
    {
        return [
            'favorite_recipes' => 'array',
            'budget_limit' => 'decimal:2',
            'dietary_restrictions' => 'array',
        ];
    }

    protected $fillable = [
        'user_id',
        'favorite_recipes',
        'budget_limit',
        'dietary_restrictions',
        'preferred_quality',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
