<?php

use App\Models\Recipe;
use Livewire\Volt\Component;
use function Livewire\Volt\{computed, layout};

layout('components.layouts.guest');

$featuredRecipes = computed(function () {
    return Recipe::with('ingredients')->take(3)->get();
});

?>

<div>
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-orange-50 via-rose-50 to-amber-50 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 overflow-hidden">
        <div class="absolute inset-0 bg-grid-zinc-900/[0.04] dark:bg-grid-white/[0.02]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="text-center">
                <flux:badge color="orange" class="mb-6 text-lg px-6 py-2">
                    평균 월 15만원 절약
                </flux:badge>

                <flux:heading size="4xl" class="mb-6 bg-gradient-to-r from-orange-600 to-rose-600 bg-clip-text text-transparent">
                    오늘 해 먹을까,<br>시켜 먹을까?
                </flux:heading>

                <flux:text size="xl" class="mb-8 text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                    데이터가 답해드립니다. 식자재 실제 원가와 배달 가격을 비교해<br>
                    가장 합리적인 식사 선택을 도와드려요.
                </flux:text>

                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <flux:button variant="primary" href="{{ route('recipes.index') }}" wire:navigate class="px-8 py-3 text-lg">
                        <flux:icon.magnifying-glass class="w-5 h-5" />
                        레시피 둘러보기
                    </flux:button>
                    <flux:button variant="outline" href="#how-it-works" class="px-8 py-3 text-lg">
                        <flux:icon.play class="w-5 h-5" />
                        어떻게 작동하나요?
                    </flux:button>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-8 max-w-3xl mx-auto">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400 mb-2">49개</div>
                        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">식자재 데이터</flux:text>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400 mb-2">60%+</div>
                        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">평균 절약률</flux:text>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400 mb-2">실시간</div>
                        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">가격 비교</flux:text>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Problem Section --}}
    <section class="py-20 bg-white dark:bg-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="3xl" class="mb-4">이런 고민, 해보셨나요?</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    매일 반복되는 식사 선택의 스트레스
                </flux:text>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg bg-zinc-50 dark:bg-zinc-900">
                    <div class="w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                        <flux:icon.currency-dollar class="w-8 h-8 text-red-600 dark:text-red-400" />
                    </div>
                    <flux:heading size="lg" class="mb-2">배달비가 부담돼요</flux:heading>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        음식값 + 배달비 + 팁까지...<br>실제로 얼마나 비싼지 모르겠어요
                    </flux:text>
                </div>

                <div class="text-center p-6 rounded-lg bg-zinc-50 dark:bg-zinc-900">
                    <div class="w-16 h-16 mx-auto mb-4 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center">
                        <flux:icon.clock class="w-8 h-8 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <flux:heading size="lg" class="mb-2">요리할 시간이 없어요</flux:heading>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        재료 사고, 손질하고, 요리하고...<br>시간이 얼마나 걸릴지 가늠이 안 돼요
                    </flux:text>
                </div>

                <div class="text-center p-6 rounded-lg bg-zinc-50 dark:bg-zinc-900">
                    <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                        <flux:icon.question-mark-circle class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <flux:heading size="lg" class="mb-2">뭐가 더 이득인지 모르겠어요</flux:heading>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        집밥이 저렴할 것 같은데<br>정말 그런지 확실하지 않아요
                    </flux:text>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-20 bg-zinc-50 dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="3xl" class="mb-4">어떻게 작동하나요?</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    3단계로 합리적인 식사 선택을 도와드려요
                </flux:text>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-orange-600 text-white rounded-full flex items-center justify-center text-xl font-bold">
                        1
                    </div>
                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 shadow-sm h-full">
                        <flux:icon.magnifying-glass class="w-12 h-12 text-orange-600 mb-4" />
                        <flux:heading size="lg" class="mb-3">레시피 검색</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">
                            먹고 싶은 메뉴를 검색하세요. 김치찌개, 된장찌개, 제육볶음 등 인기 한식 레시피를 제공합니다.
                        </flux:text>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-orange-600 text-white rounded-full flex items-center justify-center text-xl font-bold">
                        2
                    </div>
                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 shadow-sm h-full">
                        <flux:icon.calculator class="w-12 h-12 text-orange-600 mb-4" />
                        <flux:heading size="lg" class="mb-3">실시간 원가 계산</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">
                            실제 식자재 가격을 기반으로 정확한 원가를 계산합니다. 배달 음식과 즉시 비교하세요.
                        </flux:text>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-orange-600 text-white rounded-full flex items-center justify-center text-xl font-bold">
                        3
                    </div>
                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 shadow-sm h-full">
                        <flux:icon.check-circle class="w-12 h-12 text-orange-600 mb-4" />
                        <flux:heading size="lg" class="mb-3">합리적인 선택</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">
                            절약 금액과 조리 시간을 확인하고 현명한 결정을 내리세요. 데이터가 답입니다.
                        </flux:text>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Recipes --}}
    <section class="py-20 bg-white dark:bg-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <flux:heading size="3xl" class="mb-4">인기 레시피</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    지금 바로 얼마나 절약할 수 있는지 확인해보세요
                </flux:text>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-8">
                @foreach($this->featuredRecipes as $recipe)
                    @php
                        $cookingCost = $recipe->calculateCost();
                        $savings = $recipe->calculateSavings();
                        $savingsPercentage = $recipe->calculateSavingsPercentage();
                    @endphp

                    <a href="{{ route('recipes.show', $recipe) }}"
                       class="group block bg-zinc-50 dark:bg-zinc-900 rounded-lg overflow-hidden hover:shadow-lg transition-all"
                       wire:navigate>
                        <div class="h-48 bg-gradient-to-br from-orange-400 to-rose-500 flex items-center justify-center">
                            <flux:icon.fire class="w-16 h-16 text-white opacity-50" />
                        </div>
                        <div class="p-6">
                            <flux:heading size="lg" class="mb-2 group-hover:text-orange-600 transition-colors">
                                {{ $recipe->name }}
                            </flux:heading>

                            <div class="flex items-center gap-4 text-sm text-zinc-500 mb-4">
                                <div class="flex items-center gap-1">
                                    <flux:icon.clock class="w-4 h-4" />
                                    {{ $recipe->cooking_time }}분
                                </div>
                                <div class="flex items-center gap-1">
                                    <flux:icon.user-group class="w-4 h-4" />
                                    {{ $recipe->servings }}인분
                                </div>
                            </div>

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                                <div class="flex justify-between items-end mb-2">
                                    <div>
                                        <flux:text class="text-xs text-zinc-500">집밥</flux:text>
                                        <div class="text-lg font-bold text-green-600">₩{{ number_format($cookingCost) }}</div>
                                    </div>
                                    <div class="text-right">
                                        <flux:text class="text-xs text-zinc-500 line-through">
                                            배달 ₩{{ number_format($recipe->delivery_price) }}
                                        </flux:text>
                                    </div>
                                </div>
                                <flux:badge color="green" class="w-full justify-center">
                                    {{ number_format($savingsPercentage, 0) }}% 절약 (₩{{ number_format($savings) }})
                                </flux:badge>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center">
                <flux:button variant="primary" href="{{ route('recipes.index') }}" wire:navigate class="px-8 py-3 text-lg">
                    전체 레시피 보기
                    <flux:icon.arrow-right class="w-5 h-5" />
                </flux:button>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-orange-600 to-rose-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <flux:heading size="3xl" class="mb-6 text-white">
                오늘부터 합리적인 식사를 시작하세요
            </flux:heading>
            <flux:text class="text-xl mb-8 text-white/90">
                무료로 시작하고, 매달 평균 15만원을 절약해보세요
            </flux:text>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <flux:button variant="ghost" href="{{ route('recipes.index') }}" wire:navigate class="bg-white text-orange-600 hover:bg-zinc-100 px-8 py-3 text-lg">
                    <flux:icon.rocket-launch class="w-5 h-5" />
                    지금 시작하기
                </flux:button>
                <flux:button variant="ghost" href="{{ route('register') }}" wire:navigate class="border-white text-white hover:bg-white/10 px-8 py-3 text-lg">
                    <flux:icon.user-plus class="w-5 h-5" />
                    회원가입
                </flux:button>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-zinc-900 text-zinc-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-rose-500 rounded-lg flex items-center justify-center">
                        <flux:icon.fire class="w-5 h-5 text-white" />
                    </div>
                    <flux:heading size="lg" class="text-white">HashFood</flux:heading>
                </div>
                <flux:text class="text-sm">
                    데이터 기반 합리적 식사 선택 플랫폼
                </flux:text>
                <flux:text class="text-xs mt-4">
                    © 2025 HashFood. All rights reserved.
                </flux:text>
            </div>
        </div>
    </footer>
</div>
