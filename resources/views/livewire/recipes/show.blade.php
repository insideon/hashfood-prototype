<?php

use App\Models\Recipe;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use function Livewire\Volt\{computed, state};

state(['recipeId', 'servings']);

$recipe = computed(function () {
    return Recipe::with('ingredients')->findOrFail($this->recipeId);
});

$mount = function () {
    $this->servings = $this->recipe->servings;
};

$updateServings = function ($newServings) {
    $this->servings = max(1, $newServings);
};

?>

@layout('layouts.guest')

<div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <flux:button variant="ghost" href="{{ route('recipes.index') }}">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                레시피 목록으로
            </flux:button>
        </div>

        {{-- Recipe Header --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden mb-6">
            {{-- Hero Image --}}
            <div class="h-64 bg-gradient-to-br from-orange-400 to-rose-500 flex items-center justify-center">
                <flux:icon.fire class="w-32 h-32 text-white opacity-50" />
            </div>

            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <flux:heading size="2xl" class="mb-2">{{ $this->recipe->name }}</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">
                            {{ $this->recipe->description }}
                        </flux:text>
                    </div>
                    <flux:badge color="zinc">{{ $this->recipe->category }}</flux:badge>
                </div>

                {{-- Recipe Stats --}}
                <div class="grid grid-cols-3 gap-4 py-4 border-y border-zinc-200 dark:border-zinc-700">
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <flux:icon.clock class="w-5 h-5 text-zinc-400" />
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">조리시간</flux:text>
                        </div>
                        <flux:heading size="lg">{{ $this->recipe->cooking_time }}분</flux:heading>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <flux:icon.user-group class="w-5 h-5 text-zinc-400" />
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">인분</flux:text>
                        </div>
                        <flux:heading size="lg">{{ $this->recipe->servings }}인분</flux:heading>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <flux:icon.chart-bar class="w-5 h-5 text-zinc-400" />
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">난이도</flux:text>
                        </div>
                        <flux:badge
                            :color="$this->recipe->difficulty === 'easy' ? 'green' : ($this->recipe->difficulty === 'medium' ? 'yellow' : 'red')"
                        >
                            {{ $this->recipe->difficulty === 'easy' ? '쉬움' : ($this->recipe->difficulty === 'medium' ? '보통' : '어려움') }}
                        </flux:badge>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cost Comparison Card --}}
        @php
            $cookingCost = $this->recipe->calculateCost($this->servings);
            $costPerServing = $this->recipe->calculateCostPerServing($this->servings);
            $savings = $this->recipe->calculateSavings($this->servings);
            $savingsPercentage = $this->recipe->calculateSavingsPercentage($this->servings);
        @endphp

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-950 dark:to-emerald-950 rounded-lg p-6 mb-6 border border-green-200 dark:border-green-800">
            <flux:heading size="lg" class="mb-4 flex items-center gap-2">
                <flux:icon.currency-dollar class="w-6 h-6 text-green-600" />
                비용 비교
            </flux:heading>

            {{-- Servings Adjuster --}}
            <div class="mb-6">
                <flux:text class="text-sm mb-2">인분 조절</flux:text>
                <div class="flex items-center gap-4">
                    <flux:button
                        size="sm"
                        variant="ghost"
                        wire:click="updateServings({{ $this->servings - 1 }})"
                        :disabled="$this->servings <= 1"
                    >
                        <flux:icon.minus class="w-4 h-4" />
                    </flux:button>
                    <div class="px-4 py-2 bg-white dark:bg-zinc-800 rounded-lg min-w-[80px] text-center">
                        <flux:heading size="lg">{{ $this->servings }}</flux:heading>
                        <flux:text class="text-xs text-zinc-500">인분</flux:text>
                    </div>
                    <flux:button
                        size="sm"
                        variant="ghost"
                        wire:click="updateServings({{ $this->servings + 1 }})"
                    >
                        <flux:icon.plus class="w-4 h-4" />
                    </flux:button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-800 rounded-lg p-6">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">집에서 요리 ({{ $this->servings }}인분)</flux:text>
                    <flux:heading size="2xl" class="text-green-600 mb-1">₩{{ number_format($cookingCost) }}</flux:heading>
                    <flux:text class="text-xs text-zinc-500">1인분당 ₩{{ number_format($costPerServing) }}</flux:text>
                </div>

                <div class="bg-white dark:bg-zinc-800 rounded-lg p-6">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">배달 주문</flux:text>
                    <flux:heading size="2xl" class="text-zinc-400 line-through mb-1">₩{{ number_format($this->recipe->delivery_price) }}</flux:heading>
                    <flux:text class="text-xs text-zinc-500">2인분 기준</flux:text>
                </div>
            </div>

            @if($savings > 0)
                <div class="mt-6 p-4 bg-green-600 text-white rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:text class="text-sm opacity-90">총 절약 금액</flux:text>
                            <div class="text-3xl font-bold">₩{{ number_format($savings) }}</div>
                        </div>
                        <flux:badge color="white" class="text-lg px-4 py-2">
                            {{ number_format($savingsPercentage, 1) }}% 절약!
                        </flux:badge>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Ingredients --}}
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                <flux:heading size="lg" class="mb-4 flex items-center gap-2">
                    <flux:icon.shopping-cart class="w-5 h-5" />
                    재료 ({{ $this->servings }}인분 기준)
                </flux:heading>

                <div class="space-y-3">
                    @php
                        $multiplier = $this->servings / $this->recipe->servings;
                    @endphp

                    @foreach($this->recipe->ingredients as $ingredient)
                        @php
                            $adjustedQuantity = $ingredient->pivot->quantity * $multiplier;
                            $ingredientCost = $adjustedQuantity * $ingredient->current_price;
                        @endphp

                        <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                            <div class="flex-1">
                                <flux:text class="font-medium">{{ $ingredient->name }}</flux:text>
                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ number_format($adjustedQuantity, 1) }}{{ $ingredient->unit }}
                                </flux:text>
                            </div>
                            <flux:text class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">
                                ₩{{ number_format($ingredientCost) }}
                            </flux:text>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Instructions --}}
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                <flux:heading size="lg" class="mb-4 flex items-center gap-2">
                    <flux:icon.document-text class="w-5 h-5" />
                    조리 방법
                </flux:heading>

                <div class="space-y-4">
                    @foreach(explode("\n", $this->recipe->instructions) as $index => $step)
                        @if(trim($step))
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400 rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <flux:text class="flex-1 pt-1">{{ trim($step) }}</flux:text>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
