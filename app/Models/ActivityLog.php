<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected function casts(): array
    {
        return [
            'saved_amount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    protected $fillable = [
        'user_id',
        'recipe_id',
        'decision_type',
        'saved_amount',
        'metadata',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
