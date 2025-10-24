<?php

use App\Models\Recipe;
use function Livewire\Volt\{layout};

layout('components.layouts.guest');

?>

<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    {{-- Hero Section --}}
    <div class="bg-gray-50 dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    해 먹을까, 시켜 먹을까?
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    실제 식자재 원가로 비교하는 가장 합리적인 식사 선택
                </p>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-gray-50 dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-xl flex items-center justify-center">
                        <flux:icon.fire class="w-6 h-6 text-orange-600" />
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ Recipe::count() }}개
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
                            ₩{{ number_format(Recipe::get()->avg(function($r) { return $r->calculateSavings(); })) }}
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
                            {{ number_format(Recipe::get()->avg(function($r) { return $r->calculateSavingsPercentage(); }), 0) }}%
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">평균 절약률</div>
                    </div>
                </div>
            </div>
        </div>

                    {{-- Recipe Table --}}
                    <div class="mb-8">
                        @livewire('custom-recipe-table')
                    </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
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
