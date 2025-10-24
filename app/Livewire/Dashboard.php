<?php

namespace App\Livewire;

use App\Config\AppConstants;
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

    public function mount(RecommendationService $recommendationService): void
    {
        $this->user = Auth::user();
        $this->userPreferences = $this->user->userPreferences ?? UserPreference::create([
            'user_id' => $this->user->id,
            'budget_limit' => AppConstants::DEFAULT_BUDGET_LIMIT,
            'preferred_quality' => AppConstants::DEFAULT_QUALITY,
        ]);

        $this->loadStatistics($recommendationService);
    }

    public function loadStatistics(RecommendationService $recommendationService): void
    {
        $this->loadSavingsData();
        $this->loadDecisionData();
        $this->loadFavoriteRecipes();
        $this->loadRecentActivity();
        $this->loadTrendData();
        $this->loadRecommendations($recommendationService);
    }

    private function loadSavingsData(): void
    {
        $this->totalSavings = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->sum('saved_amount');

        $this->monthlySavings = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('saved_amount');

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
        $driver = DB::getDriverName();

        // 최근 6개월 트렌드
        $monthSelect = match ($driver) {
            'sqlite' => DB::raw('strftime("%Y-%m", created_at) as month'),
            'mysql', 'mariadb' => DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            'pgsql' => DB::raw('TO_CHAR(created_at, \'YYYY-MM\') as month'),
            default => DB::raw('strftime("%Y-%m", created_at) as month'),
        };

        $this->monthlyTrend = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->select(
                $monthSelect,
                DB::raw('SUM(saved_amount) as total_savings'),
                DB::raw('COUNT(*) as cooking_count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 최근 4주 트렌드
        $weekSelect = match ($driver) {
            'sqlite' => DB::raw('strftime("%W", created_at) as week'),
            'mysql', 'mariadb' => DB::raw('WEEK(created_at) as week'),
            'pgsql' => DB::raw('EXTRACT(WEEK FROM created_at) as week'),
            default => DB::raw('strftime("%W", created_at) as week'),
        };

        $this->weeklyTrend = ActivityLog::where('user_id', $this->user->id)
            ->where('decision_type', 'cook')
            ->select(
                $weekSelect,
                DB::raw('SUM(saved_amount) as total_savings'),
                DB::raw('COUNT(*) as cooking_count')
            )
            ->where('created_at', '>=', now()->subWeeks(4))
            ->groupBy('week')
            ->orderBy('week')
            ->get();
    }

    private function loadRecommendations(RecommendationService $recommendationService): void
    {
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
