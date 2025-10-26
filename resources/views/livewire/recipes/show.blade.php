<?php

use App\Models\ActivityLog;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\computed;
use function Livewire\Volt\layout;
use function Livewire\Volt\state;

layout('components.layouts.guest');

state([
    'recipeId',
    'servings' => null,
    'toastMessage' => null,
]);

$recipe = computed(function () {
    return Recipe::with('ingredients')->findOrFail($this->recipeId);
});

$effectiveServings = computed(function () {
    $servings = (int) ($this->servings ?? 0);

    if ($servings <= 0) {
        $base = (int) ($this->recipe->servings ?? 1);
        $servings = max(1, $base);
        $this->servings = $servings;
    }

    return max(1, $servings);
});

$costSummary = computed(function () {
    $servings = $this->effectiveServings;
    $cookingCost = $this->recipe->calculateCost($servings);
    $costPerServing = $this->recipe->calculateCostPerServing($servings);
    $deliveryCost = $this->recipe->calculateDeliveryCost($servings);
    $deliveryCostPerServing = $this->recipe->calculateDeliveryCostPerServing($servings);
    $savings = $this->recipe->calculateSavings($servings);
    $savingsPercentage = $this->recipe->calculateSavingsPercentage($servings);

    return [
        'cookingCost' => $cookingCost,
        'costPerServing' => $costPerServing,
        'deliveryCost' => $deliveryCost,
        'deliveryCostPerServing' => $deliveryCostPerServing,
        'savings' => $savings,
        'savingsPercentage' => $savingsPercentage,
    ];
});

$highlightIngredients = computed(function () {
    $servings = $this->effectiveServings;
    $baseServings = max(1, $this->recipe->servings);
    $multiplier = $servings / $baseServings;

    $items = $this->recipe->ingredients->map(function ($ingredient) use ($multiplier) {
        $quantity = $ingredient->pivot->quantity * $multiplier;
        $cost = $quantity * $ingredient->current_price;

        return [
            'name' => $ingredient->name,
            'quantity' => $quantity,
            'unit' => $ingredient->unit,
            'cost' => $cost,
        ];
    })->sortByDesc('cost');

    $totalCost = $this->recipe->calculateCost($servings);

    return $items->take(5)->map(function ($item) use ($totalCost) {
        $item['percentage'] = $totalCost > 0 ? ($item['cost'] / $totalCost) * 100 : 0;

        return $item;
    })->values();
});

$instructions = computed(function () {
    return collect(preg_split("/\r\n|\r|\n/", (string) $this->recipe->instructions))
        ->map(fn ($step) => trim($step))
        ->filter()
        ->values();
});

$updated = function ($property) {
    if ($property === 'servings') {
        $next = (int) ($this->servings ?? 0);

        if ($next <= 0) {
            $next = (int) ($this->recipe->servings ?? 1);
        }

        $this->servings = max(1, $next);
        $this->toastMessage = null;
    }
};

$updateServings = function ($newServings) {
    $next = (int) ($newServings ?? 0);

    if ($next <= 0) {
        $next = (int) ($this->recipe->servings ?? 1);
    }

    $this->servings = max(1, $next);
    $this->toastMessage = null;
};

$logDecision = function ($decisionType) {
    if (! Auth::check()) {
        $this->toastMessage = '로그인 후에 결정을 기록할 수 있어요.';

        return;
    }

    $servings = $this->effectiveServings;

    $savedAmount = $decisionType === 'cook'
        ? $this->recipe->calculateSavings($servings)
        : 0;

    ActivityLog::create([
        'user_id' => Auth::id(),
        'recipe_id' => $this->recipe->id,
        'decision_type' => $decisionType,
        'saved_amount' => $savedAmount,
        'metadata' => [
            'servings' => $servings,
            'cooking_cost' => $this->recipe->calculateCost($servings),
            'delivery_price' => $this->recipe->delivery_price,
        ],
    ]);

    $this->toastMessage = $decisionType === 'cook'
        ? '요리하기로 기록했어요. 절약한 금액을 대시보드에서 확인해보세요!'
        : '배달 주문 결정을 기록했어요.';
};
?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <flux:button variant="ghost" href="{{ route('recipes.index') }}" wire:navigate>
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                레시피 목록으로
            </flux:button>
            <span class="px-3 py-1 bg-zinc-100 text-zinc-600 text-sm font-medium rounded-full dark:bg-zinc-800 dark:text-zinc-400">
                {{ $this->recipe->category }}
            </span>
        </div>

        @if($this->toastMessage)
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-700/60 dark:bg-green-900/20 dark:text-green-200">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <flux:icon.sparkles class="w-5 h-5" />
                        <flux:text class="font-semibold">{{ $this->toastMessage }}</flux:text>
                    </div>
                    <flux:button size="xs" variant="ghost" wire:click="$set('toastMessage', null)">
                        <flux:icon.x-mark class="w-4 h-4" />
                    </flux:button>
                </div>
            </div>
        @endif

        @php
            $effectiveServings = $this->effectiveServings;
            $costSummary = $this->costSummary;
        @endphp

        <div class="mb-8 overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <div class="relative">
                <div class="flex h-56 items-center justify-center bg-gradient-to-br from-orange-400 via-rose-500 to-purple-500 sm:h-64">
                    <flux:icon.fire class="h-24 w-24 text-white/70" />
                </div>
                <div class="absolute right-4 top-4 rounded-full bg-white/95 backdrop-blur-sm px-3 py-1 text-sm font-bold text-zinc-900 shadow-lg dark:bg-zinc-900/95 dark:text-white">
                    배달가 ₩{{ number_format($this->recipe->delivery_price) }}
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <flux:heading size="2xl" class="mb-3 text-zinc-900 dark:text-white">
                    {{ $this->recipe->name }}
                </flux:heading>
                <flux:text class="leading-relaxed text-zinc-600 dark:text-zinc-300">
                    {{ $this->recipe->description }}
                </flux:text>

                <div class="mt-6 grid grid-cols-2 gap-4 border-t border-zinc-200 pt-6 dark:border-zinc-700 sm:grid-cols-4">
                    <div class="flex flex-col gap-2">
                        <flux:text class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            조리시간
                        </flux:text>
                        <div class="flex items-center gap-2">
                            <flux:icon.clock class="h-5 w-5 text-orange-500 dark:text-orange-400" />
                            <flux:heading size="lg">{{ $this->recipe->cooking_time }}분</flux:heading>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <flux:text class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            기본 인분
                        </flux:text>
                        <div class="flex items-center gap-2">
                            <flux:icon.user-group class="h-5 w-5 text-orange-500 dark:text-orange-400" />
                            <flux:heading size="lg">{{ $this->recipe->servings }}인분</flux:heading>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <flux:text class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            난이도
                        </flux:text>
                        <div class="flex items-center gap-2">
                            <flux:icon.chart-bar class="h-5 w-5 text-orange-500 dark:text-orange-400" />
                            <flux:badge
                                :color="$this->recipe->difficulty === 'easy' ? 'green' : ($this->recipe->difficulty === 'medium' ? 'yellow' : 'red')"
                            >
                                {{ $this->recipe->difficulty_korean ?? ucfirst($this->recipe->difficulty) }}
                            </flux:badge>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <flux:text class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            1인분 원가
                        </flux:text>
                        <div class="flex items-center gap-2">
                            <flux:icon.currency-dollar class="h-5 w-5 text-orange-500 dark:text-orange-400" />
                            <flux:heading size="lg">₩{{ number_format($costSummary['costPerServing']) }}</flux:heading>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <flux:heading size="lg" class="flex items-center gap-2 text-zinc-900 dark:text-white">
                    <flux:icon.currency-dollar class="h-6 w-6 text-green-500" />
                    비용 비교 ({{ $effectiveServings }}인분 기준)
                </flux:heading>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                    배달 가격과 비교해 지금 얼만큼 절약할 수 있는지 확인하세요.
                </flux:text>
            </div>

            <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <flux:button
                        size="sm"
                        variant="ghost"
                        :disabled="$effectiveServings <= 1"
                        wire:click="updateServings({{ max(1, $effectiveServings - 1) }})"
                    >
                        <flux:icon.minus class="h-4 w-4" />
                    </flux:button>
                    <div class="min-w-[96px] rounded-lg bg-zinc-100 px-4 py-2 text-center dark:bg-zinc-900">
                        <flux:heading size="lg">{{ $effectiveServings }}</flux:heading>
                        <flux:text class="text-xs text-zinc-500">인분</flux:text>
                    </div>
                    <flux:button
                        size="sm"
                        variant="ghost"
                        :disabled="$effectiveServings >= 10"
                        wire:click="updateServings({{ $effectiveServings + 1 }})"
                    >
                        <flux:icon.plus class="h-4 w-4" />
                    </flux:button>
                </div>
                <div class="flex-1">
                    <input
                        type="range"
                        min="1"
                        max="10"
                        step="1"
                        wire:model.live.debounce.200ms="servings"
                        class="h-2 w-full rounded-lg bg-zinc-200 accent-orange-500 dark:bg-zinc-700"
                    >
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                        배달 주문
                    </flux:text>
                    <flux:heading size="2xl" class="mt-2 text-zinc-700 dark:text-zinc-200">
                        ₩{{ number_format($costSummary['deliveryCost']) }}
                    </flux:heading>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                        1인분당 ₩{{ number_format($costSummary['deliveryCostPerServing']) }}
                    </flux:text>
                </div>
                <div class="rounded-lg border border-green-200 bg-green-50 p-6 dark:border-green-700/60 dark:bg-green-900/10">
                    <flux:text class="text-sm font-medium text-green-700 dark:text-green-300">
                        집에서 요리
                    </flux:text>
                    <flux:heading size="2xl" class="mt-2 text-green-600 dark:text-green-400">
                        ₩{{ number_format($costSummary['cookingCost']) }}
                    </flux:heading>
                    <flux:text class="text-xs text-green-700/80 dark:text-green-300/80">
                        1인분당 ₩{{ number_format($costSummary['costPerServing']) }}
                    </flux:text>
                </div>
            </div>

            @if($costSummary['savings'] > 0)
                <div class="mt-6 flex flex-col gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-700/60 dark:bg-green-900/20 dark:text-green-200 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <flux:text class="text-sm uppercase tracking-wide text-green-600 dark:text-green-300">
                            절약 금액
                        </flux:text>
                        <div class="text-3xl font-bold">
                            ₩{{ number_format($costSummary['savings']) }}
                        </div>
                    </div>
                    <flux:badge color="green" class="text-base">
                        {{ number_format($costSummary['savingsPercentage'], 1) }}% 절약
                    </flux:badge>
                </div>
            @else
                <div class="mt-6 rounded-lg border border-zinc-200 bg-zinc-100 p-4 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/40 dark:text-zinc-200">
                    <flux:text>
                        이 레시피는 배달가와 비슷한 비용이에요. 재료의 품질이나 인분 수를 조절해보세요.
                    </flux:text>
                </div>
            @endif
        </div>

        @php
            $highlightIngredients = $this->highlightIngredients;
            $highlightTotal = $highlightIngredients->sum('cost');
            $totalCost = $costSummary['cookingCost'];
        @endphp

        <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <flux:heading size="lg" class="mb-4 flex items-center gap-2 text-zinc-900 dark:text-white">
                <flux:icon.chart-pie class="h-5 w-5" />
                주요 재료 비용 분석
            </flux:heading>

            <div class="space-y-4">
                @forelse($highlightIngredients as $index => $ingredient)
                    <div>
                        <div class="flex items-center justify-between">
                            <div>
                                <flux:text class="font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ $index + 1 }}. {{ $ingredient['name'] }}
                                </flux:text>
                                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ number_format($ingredient['quantity'], 1) }}{{ $ingredient['unit'] }}
                                </flux:text>
                            </div>
                            <div class="text-right">
                                <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">
                                    ₩{{ number_format($ingredient['cost']) }}
                                </flux:text>
                                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ number_format($ingredient['percentage'], 1) }}%
                                </flux:text>
                            </div>
                        </div>
                        <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-orange-400 to-rose-500"
                                style="width: {{ min(100, $ingredient['percentage']) }}%"
                            ></div>
                        </div>
                    </div>
                @empty
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                        재료 비용 데이터를 불러오지 못했어요.
                    </flux:text>
                @endforelse
            </div>

            <div class="mt-6 grid gap-3 border-t border-zinc-200 pt-4 text-sm dark:border-zinc-700 sm:grid-cols-2">
                <div class="flex items-center justify-between">
                    <flux:text class="text-zinc-500 dark:text-zinc-400">상위 5개 재료 비용 합계</flux:text>
                    <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">
                        ₩{{ number_format($highlightTotal) }}
                    </flux:text>
                </div>
                <div class="flex items-center justify-between">
                    <flux:text class="text-zinc-500 dark:text-zinc-400">전체 재료 비용</flux:text>
                    <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">
                        ₩{{ number_format($totalCost) }}
                    </flux:text>
                </div>
            </div>
        </div>

        @php
            $baseServings = max(1, $this->recipe->servings);
            $multiplier = $effectiveServings / $baseServings;
        @endphp

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <flux:heading size="lg" class="mb-4 flex items-center gap-2 text-zinc-900 dark:text-white">
                <flux:icon.shopping-cart class="h-5 w-5" />
                전체 재료 ({{ $effectiveServings }}인분)
            </flux:heading>
            <div class="space-y-4">
                @foreach($this->recipe->ingredients as $ingredient)
                    @php
                        $adjustedQuantity = $ingredient->pivot->quantity * $multiplier;
                        $ingredientCost = $adjustedQuantity * $ingredient->current_price;
                    @endphp
                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                        <div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ $ingredient->name }}
                            </flux:text>
                            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ number_format($adjustedQuantity, 1) }}{{ $ingredient->unit }}
                                @if($ingredient->pivot->is_optional)
                                    <span class="ml-1 text-emerald-600 dark:text-emerald-400">(선택)</span>
                                @endif
                            </flux:text>
                            @if($ingredient->pivot->notes)
                                <flux:text class="text-xs text-zinc-400 dark:text-zinc-500">
                                    {{ $ingredient->pivot->notes }}
                                </flux:text>
                            @endif
                        </div>
                        <flux:text class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">
                            ₩{{ number_format($ingredientCost) }}
                        </flux:text>
                    </div>
                @endforeach
            </div>
        </div>
        </div>

        <div class="mb-8 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <flux:heading size="lg" class="mb-4 flex items-center gap-2 text-zinc-900 dark:text-white">
                <flux:icon.document-text class="h-5 w-5" />
                조리 방법
            </flux:heading>
            <div class="space-y-4">
                @forelse($this->instructions as $index => $step)
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-semibold text-orange-600 dark:bg-orange-900/40 dark:text-orange-300">
                            {{ $index + 1 }}
                        </div>
                        <flux:text class="flex-1 leading-relaxed text-zinc-700 dark:text-zinc-200">
                            {{ $step }}
                        </flux:text>
                    </div>
                @empty
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                        아직 조리 단계가 등록되지 않았어요.
                    </flux:text>
                @endforelse
            </div>
        </div>
    </div>
</div>
