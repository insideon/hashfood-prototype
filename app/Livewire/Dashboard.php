<?php

namespace App\Livewire;

use App\Config\AppConstants;
use App\Models\ActivityLog;
use App\Models\Recipe;
use App\Models\UserPreference;
use App\Services\RecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        // 캐시 키 생성 (사용자별, 월별 캐시)
        $cacheKey = "dashboard_savings_{$this->user->id}_" . now()->format('Y-m');

        // 캐시된 데이터 사용 (5분간 캐시)
        $stats = Cache::remember($cacheKey, 300, function () {
            return ActivityLog::where('user_id', $this->user->id)
                ->where('decision_type', 'cook')
                ->selectRaw('
                    SUM(saved_amount) as total_savings,
                    SUM(CASE
                        WHEN created_at >= ? THEN saved_amount
                        ELSE 0
                    END) as monthly_savings,
                    SUM(CASE
                        WHEN created_at >= ? THEN saved_amount
                        ELSE 0
                    END) as weekly_savings
                ', [
                    now()->startOfMonth(),
                    now()->startOfWeek()
                ])
                ->first();
        });

        $this->totalSavings = $stats->total_savings ?? 0;
        $this->monthlySavings = $stats->monthly_savings ?? 0;
        $this->weeklySavings = $stats->weekly_savings ?? 0;
    }

    private function loadDecisionData(): void
    {
        // 단일 쿼리로 모든 결정 데이터를 한 번에 조회
        $stats = ActivityLog::where('user_id', $this->user->id)
            ->selectRaw('
                COUNT(CASE WHEN decision_type = ? THEN 1 END) as cooking_count,
                COUNT(CASE WHEN decision_type = ? THEN 1 END) as delivery_count
            ', ['cook', 'delivery'])
            ->first();

        $this->totalCookingDecisions = $stats->cooking_count ?? 0;
        $this->totalDeliveryDecisions = $stats->delivery_count ?? 0;
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
