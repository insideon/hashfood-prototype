<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use Illuminate\Console\Command;

class UpdateRecipeCalculations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipes:update-calculations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '모든 레시피의 계산값(집밥 원가, 절약 금액, 절약률)을 업데이트합니다';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('레시피 계산값 업데이트를 시작합니다...');

        $recipes = Recipe::with('ingredients')->get();
        $bar = $this->output->createProgressBar($recipes->count());

        foreach ($recipes as $recipe) {
            $recipe->cooking_cost = $recipe->calculateCost();
            $recipe->savings = $recipe->calculateSavings();
            $recipe->savings_percentage = $recipe->calculateSavingsPercentage();
            $recipe->saveQuietly(); // timestamps 업데이트 하지 않음

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("완료! 총 {$recipes->count()}개의 레시피를 업데이트했습니다.");

        return Command::SUCCESS;
    }
}
