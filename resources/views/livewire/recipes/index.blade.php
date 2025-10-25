<?php

use App\Models\Recipe;
use Livewire\Volt\Component;
use function Livewire\Volt\{computed, layout, state};

layout('components.layouts.guest');

state(['search' => '', 'category' => '', 'page' => 1, 'perPage' => 12]);

$recipes = computed(function () {
    return Recipe::query()
        ->with('ingredients')
        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
        ->when($this->category, fn($q) => $q->where('category', $this->category))
        ->take($this->page * $this->perPage)
        ->get();
});

$hasMore = computed(function () {
    $total = Recipe::query()
        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
        ->when($this->category, fn($q) => $q->where('category', $this->category))
        ->count();

    return ($this->page * $this->perPage) < $total;
});

$categories = computed(function () {
    return Recipe::query()
        ->select('category')
        ->distinct()
        ->pluck('category');
});

$loadMore = function () {
    $this->page++;
};

// 검색이나 카테고리 변경 시 첫 페이지로 리셋
$updated = function ($property) {
    if (in_array($property, ['search', 'category'])) {
        $this->page = 1;
    }
};

?>

<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <flux:heading size="xl" class="mb-2 text-gray-900 dark:text-white">오늘 뭐 먹지?</flux:heading>
            <flux:text class="text-zinc-600 dark:text-zinc-400">
                배달 음식보다 저렴하게 집에서 만들어보세요
            </flux:text>
        </div>

        {{-- Search & Filter --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="레시피 검색..."
                    type="search"
                />
            </div>
            <div class="w-full sm:w-48">
                <flux:select wire:model.live="category">
                    <option value="">전체 카테고리</option>
                    @foreach($this->categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        {{-- Recipe Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->recipes as $recipe)
                <a
                    href="{{ route('recipes.show', ['recipeId' => $recipe->id]) }}"
                    class="group block bg-white dark:bg-zinc-800 rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-200 dark:border-zinc-700"
                    wire:key="recipe-{{ $recipe->id }}"
                >
                    {{-- Image Placeholder --}}
                    <div class="h-48 bg-gradient-to-br from-orange-400 to-rose-500 flex items-center justify-center">
                        <flux:icon.fire class="w-16 h-16 text-white opacity-50" />
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-2">
                            <flux:heading size="lg" class="text-gray-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                {{ $recipe->name }}
                            </flux:heading>
                            <flux:badge color="zinc" size="sm">{{ $recipe->category }}</flux:badge>
                        </div>

                        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 line-clamp-2">
                            {{ $recipe->description }}
                        </flux:text>

                        {{-- Stats --}}
                        <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                            <div class="flex items-center gap-1">
                                <flux:icon.clock class="w-4 h-4" />
                                <span>{{ $recipe->cooking_time }}분</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <flux:icon.user-group class="w-4 h-4" />
                                <span>{{ $recipe->servings }}인분</span>
                            </div>
                            <flux:badge
                                :color="$recipe->difficulty === 'easy' ? 'green' : ($recipe->difficulty === 'medium' ? 'yellow' : 'red')"
                                size="sm"
                            >
                                {{ $recipe->difficulty === 'easy' ? '쉬움' : ($recipe->difficulty === 'medium' ? '보통' : '어려움') }}
                            </flux:badge>
                        </div>

                        {{-- Cost Comparison --}}
                        @php
                            $cookingCost = $recipe->calculateCost();
                            $savings = $recipe->calculateSavings();
                            $savingsPercentage = $recipe->calculateSavingsPercentage();
                        @endphp

                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <div class="flex items-end justify-between mb-1">
                                <div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">집에서 요리</div>
                                    <div class="text-lg font-bold text-green-600">₩{{ number_format($cookingCost) }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 line-through">
                                        배달 ₩{{ number_format($recipe->delivery_price) }}
                                    </div>
                                </div>
                            </div>

                            @if($savings > 0)
                                <div class="flex items-center justify-between text-sm">
                                    <flux:badge color="green" class="font-semibold">
                                        {{ number_format($savingsPercentage, 1) }}% 절약
                                    </flux:badge>
                                    <span class="text-green-600 font-semibold">
                                        ₩{{ number_format($savings) }} 저렴!
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <flux:icon.magnifying-glass class="w-12 h-12 mx-auto text-zinc-400 dark:text-zinc-500 mb-4" />
                    <flux:heading size="lg" class="mb-2 text-gray-900 dark:text-white">레시피를 찾을 수 없습니다</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        검색어나 카테고리를 변경해보세요
                    </flux:text>
                </div>
            @endforelse
        </div>

        {{-- Infinite Scroll Trigger --}}
        @if($this->hasMore)
            <div
                class="mt-8 text-center py-4"
                x-data="{
                    observe() {
                        let observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    @this.call('loadMore')
                                }
                            })
                        }, {
                            rootMargin: '100px'
                        })
                        observer.observe(this.$el)
                    }
                }"
                x-init="observe"
            >
                <div wire:loading wire:target="loadMore" class="inline-flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        @else
            <div class="mt-8 text-center py-4 text-zinc-500 dark:text-zinc-400">
                모든 레시피를 불러왔습니다
            </div>
        @endif
    </div>
</div>
