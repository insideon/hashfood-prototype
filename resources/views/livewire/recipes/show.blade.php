<?php

use App\Models\ActivityLog;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\{computed, layout, state};

layout('components.layouts.guest');

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

$logDecision = function ($decisionType) {
    if (!Auth::check()) {
        return;
    }

    $savedAmount = 0;
    if ($decisionType === 'cook') {
        $savedAmount = $this->recipe->calculateSavings($this->servings);
    }

    ActivityLog::create([
        'user_id' => Auth::id(),
        'recipe_id' => $this->recipe->id,
        'decision_type' => $decisionType,
        'saved_amount' => $savedAmount,
        'metadata' => [
            'servings' => $this->servings,
            'cooking_cost' => $this->recipe->calculateCost($this->servings),
            'delivery_price' => $this->recipe->delivery_price,
        ],
    ]);

    // 성공 메시지 표시
    $this->dispatch('decision-logged', [
        'message' => $decisionType === 'cook'
            ? "요리하기로 결정했습니다! ₩" . number_format($savedAmount) . " 절약했어요."
            : "배달 주문하기로 결정했습니다."
    ]);
};

?>

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

            {{-- Decision Buttons --}}
            @auth
                <div class="mt-6 p-6 bg-zinc-50 dark:bg-zinc-700 rounded-lg">
                    <flux:heading size="lg" class="mb-4 text-center">어떻게 하시겠어요?</flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:button
                            wire:click="logDecision('cook')"
                            color="green"
                            size="lg"
                            class="w-full py-4"
                        >
                            <flux:icon.home class="w-5 h-5 mr-2" />
                            집에서 요리하기
                            <div class="text-sm opacity-90 mt-1">
                                ₩{{ number_format($cookingCost) }} • {{ $this->recipe->cooking_time }}분
                            </div>
                        </flux:button>

                        <flux:button
                            wire:click="logDecision('delivery')"
                            color="red"
                            size="lg"
                            class="w-full py-4"
                        >
                            <flux:icon.truck class="w-5 h-5 mr-2" />
                            배달 주문하기
                            <div class="text-sm opacity-90 mt-1">
                                ₩{{ number_format($this->recipe->delivery_price) }} • 30-40분
                            </div>
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                    <flux:text class="text-blue-600 dark:text-blue-400">
                        로그인하면 결정을 기록하고 절약 통계를 확인할 수 있어요!
                    </flux:text>
                    <div class="mt-3">
                        <flux:button href="{{ route('login') }}" color="blue" variant="outline">
                            로그인하기
                        </flux:button>
                    </div>
                </div>
            @endauth
        </div>

        <!-- 주요 재료 비용 분해 -->
        <div class="mt-6 bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
            <flux:heading size="lg" class="mb-4 flex items-center gap-2">
                <flux:icon.chart-pie class="w-5 h-5" />
                주요 재료 비용 분해 (상위 5개)
            </flux:heading>

            @php
                $multiplier = $this->servings / $this->recipe->servings;
                $ingredientCosts = $this->recipe->ingredients->map(function ($ingredient) use ($multiplier) {
                    $adjustedQuantity = $ingredient->pivot->quantity * $multiplier;
                    $cost = $adjustedQuantity * $ingredient->current_price;
                    return [
                        'name' => $ingredient->name,
                        'cost' => $cost,
                        'quantity' => $adjustedQuantity,
                        'unit' => $ingredient->unit,
                        'percentage' => 0, // 나중에 계산
                    ];
                })->sortByDesc('cost')->take(5);

                $totalCost = $this->recipe->calculateCost($this->servings);
                $ingredientCosts = $ingredientCosts->map(function ($item) use ($totalCost) {
                    $item['percentage'] = $totalCost > 0 ? ($item['cost'] / $totalCost) * 100 : 0;
                    return $item;
                });
            @endphp

            <div class="space-y-4">
                @foreach($ingredientCosts as $index => $ingredient)
                    <div class="flex items-center space-x-4">
                        <!-- 순위 -->
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-orange-400 to-rose-500 rounded-full flex items-center justify-center">
                            <flux:text class="text-white font-bold text-sm">{{ $index + 1 }}</flux:text>
                        </div>

                        <!-- 재료명 -->
                        <div class="flex-1 min-w-0">
                            <flux:text class="font-medium text-zinc-900 dark:text-zinc-100 truncate">
                                {{ $ingredient['name'] }}
                            </flux:text>
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ number_format($ingredient['quantity'], 1) }}{{ $ingredient['unit'] }}
                            </flux:text>
                        </div>

                        <!-- 비용 -->
                        <div class="text-right">
                            <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">
                                ₩{{ number_format($ingredient['cost']) }}
                            </flux:text>
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ number_format($ingredient['percentage'], 1) }}%
                            </flux:text>
                        </div>

                        <!-- 진행 바 -->
                        <div class="w-24 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-orange-400 to-rose-500 rounded-full transition-all duration-300"
                                 style="width: {{ $ingredient['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 총 비용 요약 -->
            <div class="mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                        상위 5개 재료 비용 합계
                    </flux:text>
                    <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">
                        ₩{{ number_format($ingredientCosts->sum('cost')) }}
                    </flux:text>
                </div>
                <div class="flex items-center justify-between mt-1">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                        전체 재료 비용
                    </flux:text>
                    <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">
                        ₩{{ number_format($totalCost) }}
                    </flux:text>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Ingredients --}}
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                <flux:heading size="lg" class="mb-4 flex items-center gap-2">
                    <flux:icon.shopping-cart class="w-5 h-5" />
                    전체 재료 목록 ({{ $this->servings }}인분 기준)
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
