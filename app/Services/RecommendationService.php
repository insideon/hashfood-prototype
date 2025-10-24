<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Recipe;
use App\Models\UserPreference;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * 사용자에게 맞춤 레시피를 추천합니다.
     */
    public function getRecommendations(int $userId, int $limit = 5): Collection
    {
        $userPreferences = UserPreference::where('user_id', $userId)->first();
        $userActivity = $this->getUserActivity($userId);

        // 추천 점수를 계산하여 레시피를 가져옵니다
        $recipes = Recipe::with('ingredients')->get();

        $scoredRecipes = $recipes->map(function ($recipe) use ($userPreferences, $userActivity) {
            return [
                'recipe' => $recipe,
                'score' => $this->calculateRecommendationScore($recipe, $userPreferences, $userActivity),
            ];
        });

        // 점수 순으로 정렬하고 상위 N개를 반환
        return $scoredRecipes
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('recipe');
    }

    /**
     * 시간대별 추천 레시피를 가져옵니다.
     */
    public function getTimeBasedRecommendations(int $userId, int $limit = 3): Collection
    {
        $currentHour = now()->hour;
        $userPreferences = UserPreference::where('user_id', $userId)->first();

        // 시간대별 추천 로직
        $timeBasedScore = $this->getTimeBasedScore($currentHour);

        $recipes = Recipe::with('ingredients')->get();

        $scoredRecipes = $recipes->map(function ($recipe) use ($userPreferences, $timeBasedScore) {
            $baseScore = $this->calculateRecommendationScore($recipe, $userPreferences, []);

            return [
                'recipe' => $recipe,
                'score' => $baseScore + $timeBasedScore,
            ];
        });

        return $scoredRecipes
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('recipe');
    }

    /**
     * 예산 기반 추천 레시피를 가져옵니다.
     */
    public function getBudgetBasedRecommendations(int $userId, int $limit = 3): Collection
    {
        $userPreferences = UserPreference::where('user_id', $userId)->first();

        if (! $userPreferences || ! $userPreferences->budget_limit) {
            return collect();
        }

        $budgetLimit = $userPreferences->budget_limit;

        // 예산 내에서 가장 절약이 큰 레시피들을 찾습니다
        $recipes = Recipe::with('ingredients')
            ->where('delivery_price', '<=', $budgetLimit)
            ->get();

        $scoredRecipes = $recipes->map(function ($recipe) use ($budgetLimit) {
            $cookingCost = $recipe->calculateCost();
            $savings = $recipe->calculateSavings();

            // 예산 대비 절약률 계산
            $budgetUtilization = ($cookingCost / $budgetLimit) * 100;
            $savingsScore = $savings > 0 ? ($savings / $budgetLimit) * 100 : 0;

            return [
                'recipe' => $recipe,
                'score' => $savingsScore - ($budgetUtilization * 0.1), // 예산 사용률이 낮을수록 좋음
            ];
        });

        return $scoredRecipes
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('recipe');
    }

    /**
     * 추천 점수를 계산합니다.
     */
    private function calculateRecommendationScore(Recipe $recipe, ?UserPreference $userPreferences, array $userActivity): float
    {
        $score = 0;

        // 1. 절약률 점수 (40%)
        $savingsPercentage = $recipe->calculateSavingsPercentage();
        $score += ($savingsPercentage / 100) * 40;

        // 2. 선호도 점수 (30%)
        $preferenceScore = $this->calculatePreferenceScore($recipe, $userPreferences);
        $score += $preferenceScore * 30;

        // 3. 조리 용이성 점수 (20%)
        $difficultyScore = $this->calculateDifficultyScore($recipe->difficulty);
        $score += $difficultyScore * 20;

        // 4. 재료 활용도 점수 (10%)
        $ingredientScore = $this->calculateIngredientScore($recipe, $userActivity);
        $score += $ingredientScore * 10;

        return $score;
    }

    /**
     * 선호도 점수를 계산합니다.
     */
    private function calculatePreferenceScore(Recipe $recipe, ?UserPreference $userPreferences): float
    {
        if (! $userPreferences) {
            return 0.5; // 기본값
        }

        $score = 0.5; // 기본 점수

        // 즐겨찾기 레시피인지 확인
        $favoriteRecipes = $userPreferences->favorite_recipes ?? [];
        if (in_array($recipe->id, $favoriteRecipes)) {
            $score += 0.3;
        }

        // 카테고리 선호도 (간단한 예시)
        $preferredCategories = ['한식', '일식', '중식', '양식']; // 기본 선호 카테고리
        if (in_array($recipe->category, $preferredCategories)) {
            $score += 0.2;
        }

        return min($score, 1.0); // 최대 1.0
    }

    /**
     * 조리 난이도 점수를 계산합니다.
     */
    private function calculateDifficultyScore(string $difficulty): float
    {
        return match ($difficulty) {
            'easy' => 1.0,
            'medium' => 0.7,
            'hard' => 0.4,
            default => 0.5,
        };
    }

    /**
     * 재료 활용도 점수를 계산합니다.
     */
    private function calculateIngredientScore(Recipe $recipe, array $userActivity): float
    {
        // 사용자가 최근에 사용한 재료들과 겹치는 정도를 계산
        $recentIngredients = $this->getRecentIngredients($userActivity);

        if (empty($recentIngredients)) {
            return 0.5; // 기본값
        }

        $recipeIngredients = $recipe->ingredients->pluck('name')->toArray();
        $commonIngredients = array_intersect($recipeIngredients, $recentIngredients);

        $utilizationRate = count($commonIngredients) / count($recipeIngredients);

        return min($utilizationRate, 1.0);
    }

    /**
     * 시간대별 점수를 계산합니다.
     */
    private function getTimeBasedScore(int $hour): float
    {
        return match (true) {
            $hour >= 6 && $hour < 10 => 0.8,  // 아침 - 간단한 요리
            $hour >= 10 && $hour < 14 => 0.9, // 점심 - 빠른 요리
            $hour >= 14 && $hour < 18 => 0.6, // 오후 - 간식류
            $hour >= 18 && $hour < 22 => 1.0, // 저녁 - 본격적인 요리
            default => 0.5, // 기타 시간
        };
    }

    /**
     * 사용자 활동 데이터를 가져옵니다.
     */
    private function getUserActivity(int $userId): array
    {
        return ActivityLog::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->with('recipe.ingredients')
            ->get()
            ->toArray();
    }

    /**
     * 최근 사용된 재료들을 가져옵니다.
     */
    private function getRecentIngredients(array $userActivity): array
    {
        $ingredients = [];

        foreach ($userActivity as $activity) {
            if (isset($activity['recipe']['ingredients'])) {
                foreach ($activity['recipe']['ingredients'] as $ingredient) {
                    $ingredients[] = $ingredient['name'];
                }
            }
        }

        return array_unique($ingredients);
    }

    /**
     * 추천 이유를 설명합니다.
     */
    public function getRecommendationReason(Recipe $recipe, int $userId): string
    {
        $reasons = [];

        $savingsPercentage = $recipe->calculateSavingsPercentage();
        if ($savingsPercentage > 50) {
            $reasons[] = "{$savingsPercentage}% 절약 가능";
        }

        if ($recipe->difficulty === 'easy') {
            $reasons[] = '쉬운 조리법';
        }

        if ($recipe->cooking_time <= 20) {
            $reasons[] = '빠른 조리 시간';
        }

        $userPreferences = UserPreference::where('user_id', $userId)->first();
        if ($userPreferences && in_array($recipe->id, $userPreferences->favorite_recipes ?? [])) {
            $reasons[] = '즐겨찾기 레시피';
        }

        return empty($reasons) ? '추천 레시피' : implode(', ', $reasons);
    }
}
