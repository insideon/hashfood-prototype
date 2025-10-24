<?php

use App\Models\Recipe;
use Livewire\Volt\Component;
use function Livewire\Volt\{computed, layout};

layout('components.layouts.guest');

$featuredRecipes = computed(function () {
    return Recipe::with('ingredients')->take(3)->get();
});

?>

<div class="bg-white dark:bg-zinc-900">
    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        {{-- Background Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-orange-50 via-white to-rose-50 dark:from-zinc-900 dark:via-zinc-900 dark:to-zinc-800"></div>

        {{-- Animated Background Elements --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-to-br from-orange-400/20 to-rose-400/20 dark:from-orange-600/10 dark:to-rose-600/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-gradient-to-tr from-amber-400/20 to-orange-400/20 dark:from-amber-600/10 dark:to-orange-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <div class="space-y-8">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-100 dark:bg-orange-900/30 rounded-full">
                    <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-orange-900 dark:text-orange-100">평균 월 15만원 절약</span>
                </div>

                {{-- Main Heading --}}
                <div class="space-y-4">
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight">
                        <span class="block text-zinc-900 dark:text-white">오늘</span>
                        <span class="block bg-gradient-to-r from-orange-600 via-rose-500 to-pink-600 bg-clip-text text-transparent">
                            해 먹을까, 시켜 먹을까?
                        </span>
                    </h1>
                    <p class="text-xl sm:text-2xl text-zinc-600 dark:text-zinc-400 max-w-3xl mx-auto">
                        식자재 실제 원가를 비교하고, 데이터로 증명하는<br class="hidden sm:block">
                        가장 합리적인 식사 선택
                    </p>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                    <a href="{{ route('recipes.index') }}"
                       wire:navigate
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-orange-600 to-rose-600 text-white rounded-xl font-semibold hover:shadow-xl hover:scale-105 transition-all duration-200">
                        <flux:icon.magnifying-glass class="w-5 h-5" />
                        레시피 둘러보기
                    </a>
                    <a href="#how-it-works"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white rounded-xl font-semibold border-2 border-zinc-200 dark:border-zinc-700 hover:border-orange-500 hover:scale-105 transition-all duration-200">
                        <flux:icon.play class="w-5 h-5" />
                        작동 원리 보기
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto pt-12">
                    <div>
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">49+</div>
                        <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">식자재</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">70%</div>
                        <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">절약률</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">실시간</div>
                        <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">가격비교</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <flux:icon.chevron-down class="w-6 h-6 text-zinc-400" />
        </div>
    </section>

    {{-- Featured Recipes Section --}}
    <section class="py-24 bg-zinc-50 dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                    지금 가장 많이 절약하는 레시피
                </h2>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    실제 데이터로 증명된 절약 금액을 확인하세요
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($this->featuredRecipes as $recipe)
                    @php
                        $cookingCost = $recipe->calculateCost();
                        $savings = $recipe->calculateSavings();
                        $savingsPercentage = $recipe->calculateSavingsPercentage();
                    @endphp

                    <a href="{{ route('recipes.show', $recipe) }}"
                       wire:navigate
                       class="group relative bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        {{-- Image --}}
                        <div class="aspect-[4/3] bg-gradient-to-br from-orange-400 to-rose-500 flex items-center justify-center relative overflow-hidden">
                            <flux:icon.fire class="w-20 h-20 text-white opacity-30" />
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full">
                                <span class="text-sm font-bold text-orange-600">-{{ number_format($savingsPercentage, 0) }}%</span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2 group-hover:text-orange-600 transition-colors">
                                {{ $recipe->name }}
                            </h3>

                            <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                                <div class="flex items-center gap-1">
                                    <flux:icon.clock class="w-4 h-4" />
                                    {{ $recipe->cooking_time }}분
                                </div>
                                <div class="flex items-center gap-1">
                                    <flux:icon.user-group class="w-4 h-4" />
                                    {{ $recipe->servings }}인분
                                </div>
                            </div>

                            {{-- Price Comparison --}}
                            <div class="space-y-2 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-zinc-500">집밥</span>
                                    <span class="text-2xl font-bold text-green-600">₩{{ number_format($cookingCost) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-zinc-500">배달</span>
                                    <span class="text-lg font-semibold text-zinc-400 line-through">₩{{ number_format($recipe->delivery_price) }}</span>
                                </div>
                                <div class="pt-2 text-center">
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                            ₩{{ number_format($savings) }} 절약!
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('recipes.index') }}"
                   wire:navigate
                   class="inline-flex items-center gap-2 px-8 py-4 bg-orange-600 text-white rounded-xl font-semibold hover:bg-orange-700 transition-colors">
                    모든 레시피 보기
                    <flux:icon.arrow-right class="w-5 h-5" />
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-24 bg-white dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                    3단계로 시작하는 합리적인 선택
                </h2>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    복잡한 계산은 저희가, 현명한 선택은 여러분이
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                @foreach([
                    ['number' => '01', 'icon' => 'magnifying-glass', 'title' => '레시피 검색', 'desc' => '먹고 싶은 메뉴를 검색하세요. 김치찌개부터 불고기까지 다양한 한식 레시피를 준비했습니다.'],
                    ['number' => '02', 'icon' => 'calculator', 'title' => '실시간 원가 계산', 'desc' => '실제 식자재 가격 데이터를 기반으로 정확한 원가를 계산합니다. 배달 음식과 즉시 비교하세요.'],
                    ['number' => '03', 'icon' => 'check-circle', 'title' => '현명한 결정', 'desc' => '절약 금액, 조리 시간, 난이도를 확인하고 데이터 기반으로 결정하세요. 매달 15만원을 절약할 수 있어요.']
                ] as $step)
                    <div class="relative">
                        <div class="absolute -top-6 -left-6 text-8xl font-bold text-orange-100 dark:text-orange-900/20">
                            {{ $step['number'] }}
                        </div>
                        <div class="relative bg-zinc-50 dark:bg-zinc-950 rounded-2xl p-8 h-full">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-600 to-rose-600 rounded-xl flex items-center justify-center mb-6">
                                <flux:icon.{{ $step['icon'] }} class="w-7 h-7 text-white" />
                            </div>
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">
                                {{ $step['title'] }}
                            </h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ $step['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="relative py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-600 via-rose-600 to-pink-600"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMzLjMxNCAwIDYgMi42ODYgNiA2cy0yLjY4NiA2LTYgNi02LTIuNjg2LTYtNiAyLjY4Ni02IDYtNnoiIHN0cm9rZT0iI2ZmZiIgc3Ryb2tlLW9wYWNpdHk9Ii4xIi8+PC9nPjwvc3ZnPg==')] opacity-20"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h2 class="text-4xl sm:text-5xl font-bold mb-6">
                오늘부터 합리적인 식사를 시작하세요
            </h2>
            <p class="text-xl text-white/90 mb-10">
                무료로 시작하고 매달 평균 15만원씩 절약해보세요
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('recipes.index') }}"
                   wire:navigate
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-orange-600 rounded-xl font-semibold hover:bg-zinc-50 hover:scale-105 transition-all duration-200">
                    <flux:icon.rocket-launch class="w-5 h-5" />
                    지금 시작하기
                </a>
                <a href="{{ route('register') }}"
                   wire:navigate
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-transparent text-white rounded-xl font-semibold border-2 border-white hover:bg-white/10 hover:scale-105 transition-all duration-200">
                    <flux:icon.user-plus class="w-5 h-5" />
                    무료 회원가입
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-zinc-900 text-zinc-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-rose-500 rounded-lg flex items-center justify-center">
                        <flux:icon.fire class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-2xl font-bold text-white">HashFood</span>
                </div>
                <p class="text-center text-zinc-500">
                    데이터 기반 합리적 식사 선택 플랫폼
                </p>
                <p class="text-sm text-zinc-600">
                    © 2025 HashFood. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</div>
