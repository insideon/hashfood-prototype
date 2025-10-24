<?php

use App\Models\Recipe;
use Livewire\Volt\Component;
use function Livewire\Volt\{computed, layout, state};

layout('components.layouts.guest');

$recipes = computed(function () {
    return Recipe::with('ingredients')->get();
});

state(['search' => '']);

$filteredRecipes = computed(function () {
    if (empty($this->search)) {
        return $this->recipes;
    }
    
    return $this->recipes->filter(function ($recipe) {
        return stripos($recipe->name, $this->search) !== false;
    });
});

?>

<div class="min-h-screen bg-white dark:bg-zinc-900">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-orange-600 to-rose-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4">
                해 먹을까, 시켜 먹을까?
            </h1>
            <p class="text-xl text-orange-100 mb-8">
                실제 원가로 비교하는 합리적인 식사 선택
            </p>
            
            {{-- Search --}}
            <div class="max-w-md mx-auto">
                <flux:input 
                    wire:model.live="search"
                    placeholder="음식명으로 검색..."
                    class="w-full"
                />
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon.fire class="w-6 h-6 text-green-600" />
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-zinc-900 dark:text-white">
                            {{ $this->recipes->count() }}개
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">레시피</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon.currency-dollar class="w-6 h-6 text-blue-600" />
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-zinc-900 dark:text-white">
                            ₩{{ number_format($this->recipes->avg(fn($r) => $r->calculateSavings())) }}
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">평균 절약</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon.chart-bar class="w-6 h-6 text-purple-600" />
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-zinc-900 dark:text-white">
                            {{ number_format($this->recipes->avg(fn($r) => $r->calculateSavingsPercentage())), 0 }}%
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">평균 절약률</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recipe Table --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                    음식별 원가 및 절약 정보
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    실제 식자재 가격을 기반으로 계산된 원가와 배달비와의 비교
                </p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-zinc-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                음식명
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                조리시간
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                집밥 원가
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                배달비
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                절약금액
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                절약률
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                액션
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($this->filteredRecipes as $recipe)
                            @php
                                $cookingCost = $recipe->calculateCost();
                                $savings = $recipe->calculateSavings();
                                $savingsPercentage = $recipe->calculateSavingsPercentage();
                            @endphp
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-rose-500 rounded-lg flex items-center justify-center mr-3">
                                            <flux:icon.fire class="w-5 h-5 text-white" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                                {{ $recipe->name }}
                                            </div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $recipe->category }} • {{ $recipe->difficulty }}급
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-zinc-900 dark:text-white">
                                    {{ $recipe->cooking_time }}분
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <span class="font-semibold text-green-600">
                                        ₩{{ number_format($cookingCost) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <span class="font-semibold text-zinc-600 dark:text-zinc-400">
                                        ₩{{ number_format($recipe->delivery_price) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <span class="font-semibold text-green-600">
                                        ₩{{ number_format($savings) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                        {{ number_format($savingsPercentage, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('recipes.show', $recipe) }}" 
                                       wire:navigate
                                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-orange-600 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:hover:bg-orange-900/30 transition-colors">
                                        상세보기
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-zinc-500 dark:text-zinc-400">
                                        <flux:icon.magnifying-glass class="w-12 h-12 mx-auto mb-4 opacity-50" />
                                        <p class="text-lg font-medium mb-2">검색 결과가 없습니다</p>
                                        <p class="text-sm">다른 검색어로 시도해보세요</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-8 text-center">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   wire:navigate
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 transition-colors">
                    <flux:icon.user-plus class="w-5 h-5 mr-2" />
                    무료 회원가입
                </a>
                <a href="{{ route('login') }}" 
                   wire:navigate
                   class="inline-flex items-center px-6 py-3 border border-zinc-300 dark:border-zinc-600 text-base font-medium rounded-md text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <flux:icon.arrow-right class="w-5 h-5 mr-2" />
                    로그인
                </a>
            </div>
        </div>
    </div>

</div>
