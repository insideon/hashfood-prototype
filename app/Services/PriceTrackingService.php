<?php

namespace App\Services;

use App\Config\AppConstants;
use App\Models\Ingredient;
use App\Models\PriceHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PriceTrackingService
{
    /**
     * 모든 식자재의 가격을 업데이트합니다.
     */
    public function updateAllPrices(): void
    {
        $ingredients = Ingredient::all();

        foreach ($ingredients as $ingredient) {
            try {
                $this->updateIngredientPrice($ingredient);
            } catch (\Exception $e) {
                Log::error("Failed to update price for ingredient {$ingredient->id}: ".$e->getMessage());
            }
        }
    }

    /**
     * 특정 식자재의 가격을 업데이트합니다.
     */
    public function updateIngredientPrice(Ingredient $ingredient): void
    {
        // 실제 구현에서는 외부 API나 크롤링을 사용
        // 여기서는 시뮬레이션된 가격 변동을 사용
        $newPrice = $this->simulatePriceUpdate($ingredient);

        if ($newPrice !== $ingredient->current_price) {
            $this->recordPriceChange($ingredient, $newPrice, 'simulated');
            $this->checkForPriceAlerts($ingredient, $newPrice);
        }
    }

    /**
     * 가격 변동을 기록합니다.
     */
    public function recordPriceChange(Ingredient $ingredient, float $newPrice, string $source = 'manual'): void
    {
        // 이전 가격을 히스토리에 저장
        PriceHistory::create([
            'ingredient_id' => $ingredient->id,
            'price' => $ingredient->current_price,
            'source' => $source,
            'recorded_at' => now(),
        ]);

        // 현재 가격 업데이트
        $ingredient->update([
            'current_price' => $newPrice,
            'price_updated_at' => now(),
        ]);
    }

    /**
     * 가격 변동 알림을 확인합니다.
     */
    public function checkForPriceAlerts(Ingredient $ingredient, float $newPrice): void
    {
        $oldPrice = $ingredient->current_price;

        if ($oldPrice > 0) {
            $changePercentage = (($newPrice - $oldPrice) / $oldPrice) * 100;

            if (abs($changePercentage) >= AppConstants::PRICE_ALERT_THRESHOLD_PERCENTAGE) {
                $this->sendPriceAlert($ingredient, (float) $oldPrice, (float) $newPrice, $changePercentage);
            }
        }
    }

    /**
     * 가격 변동 알림을 전송합니다.
     */
    private function sendPriceAlert(Ingredient $ingredient, float $oldPrice, float $newPrice, float $changePercentage): void
    {
        Log::info("Price alert for {$ingredient->name}: {$oldPrice} -> {$newPrice} ({$changePercentage}%)");
    }

    /**
     * 가격 트렌드를 분석합니다.
     */
    public function analyzePriceTrends(int $days = AppConstants::DEFAULT_TREND_ANALYSIS_DAYS): Collection
    {
        $trends = collect();

        $ingredients = Ingredient::with(['priceHistories' => function ($query) use ($days) {
            $query->where('recorded_at', '>=', now()->subDays($days))
                ->orderBy('recorded_at');
        }])->get();

        foreach ($ingredients as $ingredient) {
            $histories = $ingredient->priceHistories;

            if ($histories->count() >= 2) {
                $firstPrice = $histories->first()->price;
                $lastPrice = $histories->last()->price;
                $changePercentage = (($lastPrice - $firstPrice) / $firstPrice) * 100;

                $trends->push([
                    'ingredient' => $ingredient,
                    'first_price' => $firstPrice,
                    'last_price' => $lastPrice,
                    'change_percentage' => $changePercentage,
                    'trend' => $changePercentage > AppConstants::PRICE_TREND_UP_THRESHOLD ? 'up' : ($changePercentage < AppConstants::PRICE_TREND_DOWN_THRESHOLD ? 'down' : 'stable'),
                    'volatility' => $this->calculateVolatility($histories),
                ]);
            }
        }

        return $trends->sortByDesc('change_percentage');
    }

    /**
     * 가격 변동성을 계산합니다.
     */
    private function calculateVolatility($histories): float
    {
        $prices = $histories->pluck('price')->toArray();

        if (count($prices) < 2) {
            return 0;
        }

        $mean = array_sum($prices) / count($prices);
        $variance = array_sum(array_map(function ($price) use ($mean) {
            return pow($price - $mean, 2);
        }, $prices)) / count($prices);

        return sqrt($variance);
    }

    /**
     * 최적 구매 시점을 추천합니다.
     */
    public function getOptimalBuyingTimes(): Collection
    {
        $trends = $this->analyzePriceTrends(AppConstants::SHORT_TERM_ANALYSIS_DAYS);

        return $trends->filter(function ($trend) {
            return $trend['trend'] === 'down' && $trend['change_percentage'] < AppConstants::OPTIMAL_BUYING_THRESHOLD;
        })->take(5);
    }

    /**
     * 가격 변동이 큰 식자재를 찾습니다.
     */
    public function getHighVolatilityIngredients(): Collection
    {
        $trends = $this->analyzePriceTrends(AppConstants::DEFAULT_TREND_ANALYSIS_DAYS);

        return $trends->filter(function ($trend) {
            return $trend['volatility'] > AppConstants::HIGH_VOLATILITY_THRESHOLD;
        })->sortByDesc('volatility')->take(10);
    }

    /**
     * 시뮬레이션된 가격 업데이트 (실제 구현에서는 외부 API 사용)
     */
    private function simulatePriceUpdate(Ingredient $ingredient): float
    {
        $currentPrice = $ingredient->current_price;

        $variation = (rand(AppConstants::PRICE_VARIATION_MIN, AppConstants::PRICE_VARIATION_MAX) / AppConstants::PRICE_VARIATION_DIVISOR);
        $newPrice = $currentPrice * (1 + $variation);

        return max($newPrice, $currentPrice * AppConstants::MINIMUM_PRICE_RATIO);
    }

    /**
     * 특정 기간의 가격 통계를 제공합니다.
     */
    public function getPriceStatistics(Ingredient $ingredient, int $days = AppConstants::DEFAULT_TREND_ANALYSIS_DAYS): array
    {
        $histories = PriceHistory::where('ingredient_id', $ingredient->id)
            ->where('recorded_at', '>=', now()->subDays($days))
            ->orderBy('recorded_at')
            ->get();

        if ($histories->isEmpty()) {
            return [
                'min_price' => $ingredient->current_price,
                'max_price' => $ingredient->current_price,
                'avg_price' => $ingredient->current_price,
                'current_price' => $ingredient->current_price,
                'price_change' => 0,
                'volatility' => 0,
            ];
        }

        $prices = $histories->pluck('price')->toArray();
        $currentPrice = $ingredient->current_price;
        $firstPrice = $histories->first()->price;

        return [
            'min_price' => min($prices),
            'max_price' => max($prices),
            'avg_price' => array_sum($prices) / count($prices),
            'current_price' => $currentPrice,
            'price_change' => $firstPrice > 0 ? (($currentPrice - $firstPrice) / $firstPrice) * 100 : 0,
            'volatility' => $this->calculateVolatility($histories),
        ];
    }

    /**
     * 가격 업데이트 작업을 스케줄링합니다.
     */
    public function schedulePriceUpdates(): void
    {
        $this->updateAllPrices();
    }
}
