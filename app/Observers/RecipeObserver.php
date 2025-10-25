<?php

namespace App\Observers;

use App\Models\Recipe;

class RecipeObserver
{
    /**
     * Handle the Recipe "saving" event.
     * 저장 전에 계산된 값들을 자동으로 업데이트
     */
    public function saving(Recipe $recipe): void
    {
        // 재료가 로드되어 있고, 계산값이 변경되지 않았을 때만 자동 계산
        if ($recipe->relationLoaded('ingredients')) {
            $recipe->cooking_cost = $recipe->calculateCost();
            $recipe->savings = $recipe->calculateSavings();
            $recipe->savings_percentage = $recipe->calculateSavingsPercentage();
        }
    }
}
