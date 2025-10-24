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

<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    {{-- Hero Section --}}
    <div class="bg-gray-50 dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    해 먹을까, 시켜 먹을까?
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    실제 식자재 원가로 비교하는 가장 합리적인 식사 선택
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
    </div>

    {{-- Main Content --}}
    <div class="bg-gray-50 dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-xl flex items-center justify-center">
                        <flux:icon.fire class="w-6 h-6 text-orange-600" />
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $this->recipes->count() }}개
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">레시피</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                        <flux:icon.currency-dollar class="w-6 h-6 text-green-600" />
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            ₩{{ number_format($this->recipes->avg(function($r) { return $r->calculateSavings(); })) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">평균 절약</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-xl flex items-center justify-center">
                        <flux:icon.chart-bar class="w-6 h-6 text-blue-600" />
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($this->recipes->avg(function($r) { return $r->calculateSavingsPercentage(); }), 0) }}%
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">평균 절약률</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recipe Table --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-zinc-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    음식별 원가 및 절약 정보
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    실제 식자재 가격을 기반으로 계산된 원가와 배달비와의 비교
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                음식명
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                조리시간
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                집밥 원가
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                배달비
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                절약금액
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                절약률
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                액션
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($this->filteredRecipes as $recipe)
                            @php
                                $cookingCost = $recipe->calculateCost();
                                $savings = $recipe->calculateSavings();
                                $savingsPercentage = $recipe->calculateSavingsPercentage();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-rose-500 rounded-xl flex items-center justify-center mr-3">
                                            <flux:icon.fire class="w-5 h-5 text-white" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $recipe->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $recipe->category }} • {{ $recipe->difficulty_korean }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $recipe->cooking_time }}분
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <span class="font-semibold text-green-600">
                                        ₩{{ number_format($cookingCost) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400">
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
                                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-orange-600 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:hover:bg-orange-900/30 transition-colors">
                                        상세보기
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
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
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- Brand --}}
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-rose-500 rounded-lg flex items-center justify-center mr-3">
                            <flux:icon.fire class="w-5 h-5 text-white" />
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">해시푸드</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 max-w-md">
                        실제 식자재 원가를 기반으로 가장 합리적인 식사 선택을 도와드리는 데이터 기반 플랫폼입니다.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-orange-600 transition-colors">
                            <flux:icon.heart class="w-5 h-5" />
                        </a>
                        <a href="#" class="text-gray-400 hover:text-orange-600 transition-colors">
                            <flux:icon.share class="w-5 h-5" />
                        </a>
                        <a href="#" class="text-gray-400 hover:text-orange-600 transition-colors">
                            <flux:icon.star class="w-5 h-5" />
                        </a>
                    </div>
                </div>

                {{-- Services --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">
                        서비스
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('recipes.index') }}" wire:navigate class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">레시피</a></li>
                        <li><a href="{{ route('price-tracking') }}" wire:navigate class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">가격 트래킹</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">AI 추천</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">소비 분석</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">
                        회사
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">회사 소개</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">채용</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">블로그</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">문의</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom --}}
            <div class="border-t border-gray-200 dark:border-zinc-700 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                        © 2025 해시푸드. All rights reserved.
                    </div>
                    <div class="flex space-x-6 text-sm">
                        <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">이용약관</a>
                        <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">개인정보처리방침</a>
                        <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">운영정책</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
