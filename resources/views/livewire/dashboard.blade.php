<div>
    <div class="space-y-6">
        <!-- 헤더 -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="2xl" class="text-zinc-900 dark:text-zinc-100">
                    안녕하세요, {{ $user->name }}님! 👋
                </flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 mt-1">
                    오늘도 합리적인 식사 선택을 도와드릴게요
                </flux:text>
            </div>
            <div class="text-right">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">이번 달 예산</flux:text>
                <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                    ₩{{ number_format($userPreferences->budget_limit ?? 0) }}
                </flux:heading>
            </div>
        </div>

        <!-- 주요 통계 카드 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- 총 절약 금액 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">총 절약 금액</flux:text>
                        <flux:heading size="2xl" class="text-green-600 mt-1">
                            ₩{{ number_format($totalSavings) }}
                        </flux:heading>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon name="currency-dollar" class="w-6 h-6 text-green-600" />
                    </div>
                </div>
            </div>

            <!-- 이번 달 절약 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">이번 달 절약</flux:text>
                        <flux:heading size="2xl" class="text-blue-600 mt-1">
                            ₩{{ number_format($monthlySavings) }}
                        </flux:heading>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon name="calendar" class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
            </div>

            <!-- 이번 주 절약 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">이번 주 절약</flux:text>
                        <flux:heading size="2xl" class="text-purple-600 mt-1">
                            ₩{{ number_format($weeklySavings) }}
                        </flux:heading>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon name="clock" class="w-6 h-6 text-purple-600" />
                    </div>
                </div>
            </div>

            <!-- 요리 선택률 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">요리 선택률</flux:text>
                        <flux:heading size="2xl" class="text-orange-600 mt-1">
                            {{ number_format($this->savingsRate, 1) }}%
                        </flux:heading>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                        <flux:icon name="chart-pie" class="w-6 h-6 text-orange-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- 차트 섹션 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 월별 절약 트렌드 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">월별 절약 트렌드</flux:heading>
                    <flux:badge color="green">최근 6개월</flux:badge>
                </div>

                @if($monthlyTrend->count() > 0)
                    <div class="space-y-3">
                        @foreach($monthlyTrend as $trend)
                            <div class="flex items-center justify-between">
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $trend->month)->format('Y년 m월') }}
                                </flux:text>
                                <div class="text-right">
                                    <flux:text class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        ₩{{ number_format($trend->total_savings) }}
                                    </flux:text>
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $trend->cooking_count }}회 요리
                                    </flux:text>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <flux:icon name="chart-bar" class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                        <flux:text class="text-zinc-500 dark:text-zinc-400">
                            아직 데이터가 없습니다.<br>
                            레시피를 확인해보세요!
                        </flux:text>
                    </div>
                @endif
            </div>

            <!-- 주간 절약 트렌드 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">주간 절약 트렌드</flux:heading>
                    <flux:badge color="blue">최근 4주</flux:badge>
                </div>

                @if($weeklyTrend->count() > 0)
                    <div class="space-y-3">
                        @foreach($weeklyTrend as $trend)
                            <div class="flex items-center justify-between">
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $trend->week }}주차
                                </flux:text>
                                <div class="text-right">
                                    <flux:text class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        ₩{{ number_format($trend->total_savings) }}
                                    </flux:text>
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $trend->cooking_count }}회 요리
                                    </flux:text>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <flux:icon name="chart-bar" class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                        <flux:text class="text-zinc-500 dark:text-zinc-400">
                            아직 데이터가 없습니다.<br>
                            레시피를 확인해보세요!
                        </flux:text>
                    </div>
                @endif
            </div>
        </div>

        <!-- 최근 활동 및 즐겨찾기 레시피 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 최근 활동 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">최근 활동</flux:heading>
                    <flux:badge color="gray">최근 5개</flux:badge>
                </div>

                @if($recentActivity->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    @if($activity->decision_type === 'cook')
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                                            <flux:icon name="home" class="w-4 h-4 text-green-600" />
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                                            <flux:icon name="truck" class="w-4 h-4 text-red-600" />
                                        </div>
                                    @endif
                                    <div>
                                        <flux:text class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $activity->recipe->name ?? '레시피 없음' }}
                                        </flux:text>
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </flux:text>
                                    </div>
                                </div>
                                @if($activity->decision_type === 'cook' && $activity->saved_amount > 0)
                                    <flux:badge color="green">₩{{ number_format($activity->saved_amount) }} 절약</flux:badge>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <flux:icon name="clock" class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                        <flux:text class="text-zinc-500 dark:text-zinc-400">
                            아직 활동 기록이 없습니다.<br>
                            레시피를 확인해보세요!
                        </flux:text>
                    </div>
                @endif
            </div>

            <!-- 즐겨찾기 레시피 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">즐겨찾기 레시피</flux:heading>
                    <flux:badge color="yellow">추천</flux:badge>
                </div>

                @if($favoriteRecipes->count() > 0)
                    <div class="space-y-3">
                        @foreach($favoriteRecipes as $recipe)
                            <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                                        <flux:icon name="heart" class="w-5 h-5 text-orange-600" />
                                    </div>
                                    <div>
                                        <flux:text class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $recipe->name }}
                                        </flux:text>
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ $recipe->cooking_time }}분 • {{ $recipe->difficulty }}급
                                        </flux:text>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <flux:text class="text-sm font-medium text-green-600">
                                        ₩{{ number_format($recipe->calculateSavings()) }}
                                    </flux:text>
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                        절약
                                    </flux:text>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <flux:icon name="heart" class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                        <flux:text class="text-zinc-500 dark:text-zinc-400">
                            즐겨찾기 레시피가 없습니다.<br>
                            레시피를 확인하고 즐겨찾기에 추가해보세요!
                        </flux:text>
                    </div>
                @endif
            </div>
        </div>

        <!-- 추천 레시피 섹션 -->
        <div class="space-y-6">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">오늘의 추천 레시피</flux:heading>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- AI 추천 -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">AI 맞춤 추천</flux:heading>
                        <flux:badge color="purple">개인화</flux:badge>
                    </div>

                    @if($recommendedRecipes->count() > 0)
                        <div class="space-y-3">
                            @foreach($recommendedRecipes as $recipe)
                                <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                                            <flux:icon.sparkles class="w-5 h-5 text-purple-600" />
                                        </div>
                                        <div>
                                            <flux:text class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $recipe->name }}
                                            </flux:text>
                                            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $recipe->cooking_time }}분 • {{ $recipe->difficulty }}급
                                            </flux:text>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <flux:text class="text-sm font-medium text-green-600">
                                            ₩{{ number_format($recipe->calculateSavings()) }}
                                        </flux:text>
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                            절약
                                        </flux:text>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <flux:icon.sparkles class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                            <flux:text class="text-zinc-500 dark:text-zinc-400">
                                추천을 위해 더 많은 활동이 필요해요!
                            </flux:text>
                        </div>
                    @endif
                </div>

                <!-- 시간대별 추천 -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">시간대별 추천</flux:heading>
                        <flux:badge color="blue">{{ now()->format('H:i') }}</flux:badge>
                    </div>

                    @if($timeBasedRecommendations->count() > 0)
                        <div class="space-y-3">
                            @foreach($timeBasedRecommendations as $recipe)
                                <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                            <flux:icon.clock class="w-5 h-5 text-blue-600" />
                                        </div>
                                        <div>
                                            <flux:text class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $recipe->name }}
                                            </flux:text>
                                            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $recipe->cooking_time }}분 • {{ $recipe->difficulty }}급
                                            </flux:text>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <flux:text class="text-sm font-medium text-green-600">
                                            ₩{{ number_format($recipe->calculateSavings()) }}
                                        </flux:text>
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                            절약
                                        </flux:text>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <flux:icon.clock class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                            <flux:text class="text-zinc-500 dark:text-zinc-400">
                                시간대별 추천이 없습니다.
                            </flux:text>
                        </div>
                    @endif
                </div>

                <!-- 예산 기반 추천 -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">예산 기반 추천</flux:heading>
                        <flux:badge color="green">₩{{ number_format($userPreferences->budget_limit ?? 0) }}</flux:badge>
                    </div>

                    @if($budgetBasedRecommendations->count() > 0)
                        <div class="space-y-3">
                            @foreach($budgetBasedRecommendations as $recipe)
                                <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                            <flux:icon.currency-dollar class="w-5 h-5 text-green-600" />
                                        </div>
                                        <div>
                                            <flux:text class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $recipe->name }}
                                            </flux:text>
                                            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                                ₩{{ number_format($recipe->calculateCost()) }} • {{ $recipe->cooking_time }}분
                                            </flux:text>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <flux:text class="text-sm font-medium text-green-600">
                                            ₩{{ number_format($recipe->calculateSavings()) }}
                                        </flux:text>
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                            절약
                                        </flux:text>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <flux:icon.currency-dollar class="w-12 h-12 text-zinc-400 mx-auto mb-3" />
                            <flux:text class="text-zinc-500 dark:text-zinc-400">
                                예산 설정이 필요해요!
                            </flux:text>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-blue-500 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="lg" class="text-white">오늘 뭐 먹을까요?</flux:heading>
                    <flux:text class="text-green-100 mt-1">
                        레시피를 확인하고 합리적인 선택을 해보세요
                    </flux:text>
                </div>
                <div class="flex space-x-3">
                    <flux:button href="{{ route('recipes.index') }}" color="white" variant="outline">
                        레시피 보기
                    </flux:button>
                    <flux:button href="{{ route('onboarding') }}" color="white" variant="outline">
                        설정
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>