<div>
    <div class="space-y-6">
        <!-- 헤더 -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="2xl" class="text-zinc-900 dark:text-zinc-100">
                    식자재 가격 트래킹 📈
                </flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 mt-1">
                    실시간 가격 변동과 최적 구매 시점을 확인하세요
                </flux:text>
            </div>
            <div class="flex items-center space-x-3">
                <flux:select wire:model.live="selectedDays" class="w-32">
                    <option value="7">최근 7일</option>
                    <option value="30">최근 30일</option>
                    <option value="90">최근 90일</option>
                </flux:select>
                <flux:button wire:click="refreshPrices" color="blue" variant="outline">
                    <flux:icon.arrow-path class="w-4 h-4 mr-2" />
                    가격 업데이트
                </flux:button>
            </div>
        </div>

        <!-- 주요 통계 카드 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 상승 추세 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">상승 추세</flux:text>
                        <flux:heading size="2xl" class="text-red-600 mt-1">
                            {{ $priceTrends->where('trend', 'up')->count() }}개
                        </flux:heading>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon.arrow-trending-up class="w-6 h-6 text-red-600" />
                    </div>
                </div>
            </div>

            <!-- 하락 추세 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">하락 추세</flux:text>
                        <flux:heading size="2xl" class="text-green-600 mt-1">
                            {{ $priceTrends->where('trend', 'down')->count() }}개
                        </flux:heading>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon.arrow-trending-down class="w-6 h-6 text-green-600" />
                    </div>
                </div>
            </div>

            <!-- 최적 구매 시점 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">최적 구매</flux:text>
                        <flux:heading size="2xl" class="text-blue-600 mt-1">
                            {{ $optimalBuyingTimes->count() }}개
                        </flux:heading>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon.shopping-cart class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- 최적 구매 시점 -->
        @if($optimalBuyingTimes->count() > 0)
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">🛒 최적 구매 시점</flux:heading>
                    <flux:badge color="green">지금 사세요!</flux:badge>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($optimalBuyingTimes as $item)
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex items-center justify-between mb-2">
                                <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $item['ingredient']->name }}
                                </flux:text>
                                <flux:badge color="green">
                                    {{ number_format($item['change_percentage'], 1) }}% 하락
                                </flux:badge>
                            </div>
                            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                ₩{{ number_format($item['first_price']) }} → ₩{{ number_format($item['last_price']) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 가격 변동이 큰 식자재 -->
        @if($highVolatilityIngredients->count() > 0)
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">📊 높은 변동성 식자재</flux:heading>
                    <flux:badge color="orange">주의</flux:badge>
                </div>

                <div class="space-y-3">
                    @foreach($highVolatilityIngredients as $item)
                        <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                                    <flux:icon.chart-bar class="w-5 h-5 text-orange-600" />
                                </div>
                                <div>
                                    <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $item['ingredient']->name }}
                                    </flux:text>
                                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                        변동성: {{ number_format($item['volatility']) }}
                                    </flux:text>
                                </div>
                            </div>
                            <div class="text-right">
                                <flux:text class="text-sm font-medium {{ $item['change_percentage'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $item['change_percentage'] > 0 ? '+' : '' }}{{ number_format($item['change_percentage'], 1) }}%
                                </flux:text>
                                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                    ₩{{ number_format($item['last_price']) }}
                                </flux:text>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 식자재별 상세 정보 -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
            <flux:heading size="lg" class="mb-4 text-zinc-900 dark:text-zinc-100">🔍 식자재별 상세 분석</flux:heading>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- 식자재 목록 -->
                <div>
                    <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">식자재 선택</flux:text>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($priceTrends as $trend)
                            <button
                                wire:click="selectIngredient({{ $trend['ingredient']->id }})"
                                class="w-full p-3 text-left border rounded-lg transition-all duration-200 {{ $selectedIngredient && $selectedIngredient->id === $trend['ingredient']->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $trend['ingredient']->name }}
                                        </flux:text>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $trend['ingredient']->category }}
                                        </flux:text>
                                    </div>
                                    <div class="text-right">
                                        <flux:text class="text-sm font-medium {{ $trend['change_percentage'] > 0 ? 'text-red-600' : ($trend['change_percentage'] < 0 ? 'text-green-600' : 'text-zinc-600') }}">
                                            {{ $trend['change_percentage'] > 0 ? '+' : '' }}{{ number_format($trend['change_percentage'], 1) }}%
                                        </flux:text>
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                            ₩{{ number_format($trend['last_price']) }}
                                        </flux:text>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 선택된 식자재 통계 -->
                @if($selectedIngredient && !empty($ingredientStatistics))
                    <div>
                        <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">
                            {{ $selectedIngredient->name }} 상세 통계
                        </flux:text>

                        <div class="space-y-4">
                            <!-- 현재 가격 -->
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">현재 가격</flux:text>
                                    <flux:text class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                        ₩{{ number_format($ingredientStatistics['current_price']) }}
                                    </flux:text>
                                </div>
                            </div>

                            <!-- 가격 범위 -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">최저가</flux:text>
                                    <flux:text class="text-sm font-semibold text-green-600">
                                        ₩{{ number_format($ingredientStatistics['min_price']) }}
                                    </flux:text>
                                </div>
                                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">최고가</flux:text>
                                    <flux:text class="text-sm font-semibold text-red-600">
                                        ₩{{ number_format($ingredientStatistics['max_price']) }}
                                    </flux:text>
                                </div>
                            </div>

                            <!-- 평균 가격 -->
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">평균 가격</flux:text>
                                    <flux:text class="font-semibold text-blue-600">
                                        ₩{{ number_format($ingredientStatistics['avg_price']) }}
                                    </flux:text>
                                </div>
                            </div>

                            <!-- 가격 변동 -->
                            <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">변동률</flux:text>
                                    <flux:text class="font-semibold {{ $ingredientStatistics['price_change'] > 0 ? 'text-red-600' : ($ingredientStatistics['price_change'] < 0 ? 'text-green-600' : 'text-zinc-600') }}">
                                        {{ $ingredientStatistics['price_change'] > 0 ? '+' : '' }}{{ number_format($ingredientStatistics['price_change'], 1) }}%
                                    </flux:text>
                                </div>
                            </div>

                            <!-- 변동성 -->
                            <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">변동성</flux:text>
                                    <flux:text class="font-semibold text-purple-600">
                                        {{ number_format($ingredientStatistics['volatility']) }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center h-64">
                        <div class="text-center">
                            <flux:icon.chart-bar class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                            <flux:text class="text-zinc-500 dark:text-zinc-400">
                                식자재를 선택하면 상세 통계를 확인할 수 있어요
                            </flux:text>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>