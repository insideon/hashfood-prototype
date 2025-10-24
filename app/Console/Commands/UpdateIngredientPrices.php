<?php

namespace App\Console\Commands;

use App\Services\PriceTrackingService;
use Illuminate\Console\Command;

class UpdateIngredientPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingredients:update-prices {--ingredient= : 특정 식자재 ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '식자재 가격을 업데이트합니다';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $priceTrackingService = new PriceTrackingService;

        $ingredientId = $this->option('ingredient');

        if ($ingredientId) {
            $ingredient = \App\Models\Ingredient::find($ingredientId);

            if (! $ingredient) {
                $this->error("식자재 ID {$ingredientId}를 찾을 수 없습니다.");

                return 1;
            }

            $this->info("식자재 '{$ingredient->name}'의 가격을 업데이트 중...");
            $priceTrackingService->updateIngredientPrice($ingredient);
            $this->info("가격 업데이트 완료: ₩{$ingredient->fresh()->current_price}");
        } else {
            $this->info('모든 식자재의 가격을 업데이트 중...');
            $priceTrackingService->updateAllPrices();
            $this->info('모든 식자재 가격 업데이트 완료');
        }

        return 0;
    }
}
