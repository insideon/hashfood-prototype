<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Recipe;
use App\Models\UserPreference;
use App\Services\RecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;

    public $userPreferences;

    // 통계 데이터
    public $totalSavings = 0;

    public $monthlySavings = 0;

    public $weeklySavings = 0;

    public $totalCookingDecisions = 0;

    public $totalDeliveryDecisions = 0;

    public $favoriteRecipes = [];

    public $recentActivity = [];

    public $monthlyTrend = [];

    public $weeklyTrend = [];

    public $recommendedRecipes = [];

    public $timeBasedRecommendations = [];

    public $budgetBasedRecommendations = [];

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->userPreferences = $this->user->userPreferences ?? UserPreference::create([
            'user_id' => $this->user->id,
            'budget_limit' => 100000, // 기본값 10만원
            'preferred_quality' => 'normal',
        ]);

        $this->loadStatistics();
    }

    public function loadStatistics(): void
    {
        $this->loadSavingsData();
        $this->loadDecisionData();
        $this->loadFavoriteRecipes();
        $this->loadRecentActivity();
        $this->loadTrendData();
        $this->loadRecommendations();
    }

    private function loadSavingsData(): void
    {
        // 총 절약 금액
        $this->totalSavings = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->sum('saved_amount');

        // 이번 달 절약 금액
        $this->monthlySavings = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('saved_amount');

        // 이번 주 절약 금액
        $this->weeklySavings = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('saved_amount');
    }

    private function loadDecisionData(): void
    {
        $this->totalCookingDecisions = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->count();

        $this->totalDeliveryDecisions = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'delivery')
            ->count();
    }

    private function loadFavoriteRecipes(): void
    {
        $favoriteRecipeIds = $this->userPreferences->favorite_recipes ?? [];

        if (! empty($favoriteRecipeIds)) {
            $this->favoriteRecipes = Recipe::whereIn('id', $favoriteRecipeIds)
                ->with('ingredients')
                ->limit(3)
                ->get();
        }
    }

    private function loadRecentActivity(): void
    {
        $this->recentActivity = ActivityLog::where('user_id', $this->user->id)
            ->with('recipe')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function loadTrendData(): void
    {
        // 최근 6개월 트렌드
        $this->monthlyTrend = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->select(
                DB::raw('strftime("%Y-%m", created_at) as month'),
                DB::raw('SUM(saved_amount) as total_savings'),
                DB::raw('COUNT(*) as cooking_count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 최근 4주 트렌드
        $this->weeklyTrend = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->select(
                DB::raw('strftime("%W", created_at) as week'),
                DB::raw('SUM(saved_amount) as total_savings'),
                DB::raw('COUNT(*) as cooking_count')
            )
            ->where('created_at', '>=', now()->subWeeks(4))
            ->groupBy('week')
            ->orderBy('week')
            ->get();
    }

    private function loadRecommendations(): void
    {
        $recommendationService = new RecommendationService;

        $this->recommendedRecipes = $recommendationService->getRecommendations($this->user->id, 3);
        $this->timeBasedRecommendations = $recommendationService->getTimeBasedRecommendations($this->user->id, 3);
        $this->budgetBasedRecommendations = $recommendationService->getBudgetBasedRecommendations($this->user->id, 3);
    }

    public function getSavingsRateProperty(): float
    {
        $totalDecisions = $this->totalCookingDecisions + $this->totalDeliveryDecisions;

        if ($totalDecisions === 0) {
            return 0;
        }

        return ($this->totalCookingDecisions / $totalDecisions) * 100;
    }

    public function getBudgetUtilizationProperty(): float
    {
        if (! $this->userPreferences->budget_limit) {
            return 0;
        }

        $monthlySpent = ActivityLog::where('user_id', $this->user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('saved_amount');

        return ($monthlySpent / $this->userPreferences->budget_limit) * 100;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
