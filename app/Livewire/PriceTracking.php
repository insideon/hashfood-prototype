<?php

namespace App\Livewire;

use App\Config\AppConstants;
use App\Models\Ingredient;
use App\Services\PriceTrackingService;
use Livewire\Component;

class PriceTracking extends Component
{
    public $priceTrends = [];

    public $optimalBuyingTimes = [];

    public $highVolatilityIngredients = [];

    public $selectedIngredient = null;

    public $ingredientStatistics = [];

    public $selectedDays = 30;

    public function mount(PriceTrackingService $priceTrackingService): void
    {
        $this->loadData($priceTrackingService);
    }

    public function loadData(PriceTrackingService $priceTrackingService): void
    {
        $this->priceTrends = $priceTrackingService->analyzePriceTrends($this->selectedDays);
        $this->optimalBuyingTimes = $priceTrackingService->getOptimalBuyingTimes();
        $this->highVolatilityIngredients = $priceTrackingService->getHighVolatilityIngredients();
    }

    public function selectIngredient(int $ingredientId, PriceTrackingService $priceTrackingService): void
    {
        $this->validate([
            'selectedIngredient' => 'nullable|exists:ingredients,id',
        ]);

        $this->selectedIngredient = Ingredient::find($ingredientId);

        if ($this->selectedIngredient) {
            $this->ingredientStatistics = $priceTrackingService->getPriceStatistics($this->selectedIngredient, $this->selectedDays);
        }
    }

    public function updateDays(int $days, PriceTrackingService $priceTrackingService): void
    {
        $this->validate([
            'selectedDays' => 'required|integer|min:'.AppConstants::MIN_TRACKING_DAYS.'|max:'.AppConstants::MAX_TRACKING_DAYS,
        ]);

        $this->selectedDays = $days;
        $this->loadData($priceTrackingService);

        if ($this->selectedIngredient) {
            $this->ingredientStatistics = $priceTrackingService->getPriceStatistics($this->selectedIngredient, $this->selectedDays);
        }
    }

    public function refreshPrices(PriceTrackingService $priceTrackingService): void
    {
        $priceTrackingService->updateAllPrices();

        $this->loadData($priceTrackingService);

        if ($this->selectedIngredient) {
            $this->ingredientStatistics = $priceTrackingService->getPriceStatistics($this->selectedIngredient, $this->selectedDays);
        }

        $this->dispatch('prices-updated');
    }

    public function render()
    {
        return view('livewire.price-tracking');
    }
}
