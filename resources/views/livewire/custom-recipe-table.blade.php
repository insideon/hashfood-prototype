<div class="space-y-6">
    {{-- 검색 및 필터 --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            {{-- 검색 --}}
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <flux:icon.magnifying-glass class="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="음식명으로 검색..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    />
                </div>
            </div>

            {{-- 카테고리 필터 --}}
            <div class="lg:w-48">
                <select
                    wire:model.live="categoryFilter"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                    <option value="">전체 카테고리</option>
                    <option value="한식">한식</option>
                    <option value="중식">중식</option>
                    <option value="일식">일식</option>
                    <option value="양식">양식</option>
                    <option value="디저트">디저트</option>
                    <option value="간식">간식</option>
                </select>
            </div>

            {{-- 난이도 필터 --}}
            <div class="lg:w-48">
                <select
                    wire:model.live="difficultyFilter"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                    <option value="">전체 난이도</option>
                    <option value="easy">쉬움</option>
                    <option value="medium">보통</option>
                    <option value="hard">어려움</option>
                </select>
            </div>
        </div>
    </div>

    {{-- 테이블 --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-zinc-900 dark:to-zinc-800">
                    <tr>
                        {{-- 음식명 --}}
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button
                                wire:click="sortBy('name')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                            >
                                <span>음식명</span>
                                @if($sortField === 'name')
                                    @if($sortDirection === 'asc')
                                        <flux:icon.chevron-up class="w-4 h-4" />
                                    @else
                                        <flux:icon.chevron-down class="w-4 h-4" />
                                    @endif
                                @else
                                    <flux:icon.chevron-up-down class="w-4 h-4 opacity-50" />
                                @endif
                            </button>
                        </th>

                        {{-- 카테고리 --}}
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button
                                wire:click="sortBy('category')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                            >
                                <span>카테고리</span>
                                @if($sortField === 'category')
                                    @if($sortDirection === 'asc')
                                        <flux:icon.chevron-up class="w-4 h-4" />
                                    @else
                                        <flux:icon.chevron-down class="w-4 h-4" />
                                    @endif
                                @else
                                    <flux:icon.chevron-up-down class="w-4 h-4 opacity-50" />
                                @endif
                            </button>
                        </th>

                        {{-- 난이도 --}}
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button
                                wire:click="sortBy('difficulty')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                            >
                                <span>난이도</span>
                                @if($sortField === 'difficulty')
                                    @if($sortDirection === 'asc')
                                        <flux:icon.chevron-up class="w-4 h-4" />
                                    @else
                                        <flux:icon.chevron-down class="w-4 h-4" />
                                    @endif
                                @else
                                    <flux:icon.chevron-up-down class="w-4 h-4 opacity-50" />
                                @endif
                            </button>
                        </th>

                        {{-- 조리시간 --}}
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button
                                wire:click="sortBy('cooking_time')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                            >
                                <span>조리시간</span>
                                @if($sortField === 'cooking_time')
                                    @if($sortDirection === 'asc')
                                        <flux:icon.chevron-up class="w-4 h-4" />
                                    @else
                                        <flux:icon.chevron-down class="w-4 h-4" />
                                    @endif
                                @else
                                    <flux:icon.chevron-up-down class="w-4 h-4 opacity-50" />
                                @endif
                            </button>
                        </th>

                        {{-- 집밥 원가 --}}
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            집밥 원가
                        </th>

                        {{-- 배달비 --}}
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button
                                wire:click="sortBy('delivery_price')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300 transition-colors ml-auto"
                            >
                                <span>배달비</span>
                                @if($sortField === 'delivery_price')
                                    @if($sortDirection === 'asc')
                                        <flux:icon.chevron-up class="w-4 h-4" />
                                    @else
                                        <flux:icon.chevron-down class="w-4 h-4" />
                                    @endif
                                @else
                                    <flux:icon.chevron-up-down class="w-4 h-4 opacity-50" />
                                @endif
                            </button>
                        </th>

                        {{-- 절약금액 --}}
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            절약금액
                        </th>

                        {{-- 절약률 --}}
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            절약률
                        </th>

                        {{-- 액션 --}}
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            액션
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($recipes as $recipe)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors duration-150">
                            {{-- 음식명 --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $recipe->name }}
                                </div>
                            </td>

                            {{-- 카테고리 --}}
                            <td class="px-6 py-4">
                                @php
                                    $categoryColors = [
                                        '한식' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                        '중식' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400',
                                        '일식' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                        '양식' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                        '디저트' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400',
                                        '간식' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                    ];
                                    $color = $categoryColors[$recipe->category] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                                    {{ $recipe->category }}
                                </span>
                            </td>

                            {{-- 난이도 --}}
                            <td class="px-6 py-4">
                                @php
                                    $difficultyColors = [
                                        'easy' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                        'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'hard' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                    ];
                                    $difficultyTexts = [
                                        'easy' => '쉬움',
                                        'medium' => '보통',
                                        'hard' => '어려움',
                                    ];
                                    $color = $difficultyColors[$recipe->difficulty] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                                    $text = $difficultyTexts[$recipe->difficulty] ?? $recipe->difficulty;
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                                    {{ $text }}
                                </span>
                            </td>

                            {{-- 조리시간 --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <flux:icon.clock class="w-4 h-4 mr-1" />
                                    {{ $recipe->cooking_time }}분
                                </div>
                            </td>

                            {{-- 집밥 원가 --}}
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ₩{{ number_format($recipe->calculateCost()) }}
                                </div>
                            </td>

                            {{-- 배달비 --}}
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ₩{{ number_format($recipe->delivery_price) }}
                                </div>
                            </td>

                            {{-- 절약금액 --}}
                            <td class="px-6 py-4 text-right">
                                @php
                                    $savings = $recipe->calculateSavings();
                                    $savingsColor = $savings > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                                @endphp
                                <div class="text-sm font-semibold {{ $savingsColor }}">
                                    ₩{{ number_format($savings) }}
                                </div>
                            </td>

                            {{-- 절약률 --}}
                            <td class="px-6 py-4 text-right">
                                @php
                                    $percentage = $recipe->calculateSavingsPercentage();
                                    $percentageColor = $percentage > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                                @endphp
                                <div class="text-sm font-semibold {{ $percentageColor }}">
                                    {{ number_format($percentage, 0) }}%
                                </div>
                            </td>

                            {{-- 액션 --}}
                            <td class="px-6 py-4 text-center">
                                <a
                                    href="{{ route('recipes.show', $recipe) }}"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-gradient-to-r from-orange-500 to-rose-500 hover:from-orange-600 hover:to-rose-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200"
                                >
                                    <flux:icon.eye class="w-3 h-3 mr-1" />
                                    보기
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <flux:icon.magnifying-glass class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" />
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">레시피를 찾을 수 없습니다</h3>
                                    <p class="text-gray-500 dark:text-gray-400">다른 검색어나 필터를 시도해보세요.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 페이지네이션 --}}
        @if($recipes->hasPages())
            <div class="bg-white dark:bg-zinc-800 px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                        <span>
                            {{ $recipes->firstItem() }} - {{ $recipes->lastItem() }} / {{ $recipes->total() }}개 결과
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $recipes->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>