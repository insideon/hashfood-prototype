<?php

namespace App\Livewire;

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

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $priceTrackingService = new PriceTrackingService;

        $this->priceTrends = $priceTrackingService->analyzePriceTrends($this->selectedDays);
        $this->optimalBuyingTimes = $priceTrackingService->getOptimalBuyingTimes();
        $this->highVolatilityIngredients = $priceTrackingService->getHighVolatilityIngredients();
    }

    public function selectIngredient(int $ingredientId): void
    {
        $this->selectedIngredient = Ingredient::find($ingredientId);

        if ($this->selectedIngredient) {
            $priceTrackingService = new PriceTrackingService;
            $this->ingredientStatistics = $priceTrackingService->getPriceStatistics($this->selectedIngredient, $this->selectedDays);
        }
    }

    public function updateDays(int $days): void
    {
        $this->selectedDays = $days;
        $this->loadData();

        if ($this->selectedIngredient) {
            $priceTrackingService = new PriceTrackingService;
            $this->ingredientStatistics = $priceTrackingService->getPriceStatistics($this->selectedIngredient, $this->selectedDays);
        }
    }

    public function refreshPrices(): void
    {
        $priceTrackingService = new PriceTrackingService;
        $priceTrackingService->updateAllPrices();

        $this->loadData();

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
