<div class="w-full">
    {{-- 검색 및 도구 모음 --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
        {{-- 검색 --}}
        <div class="w-full sm:w-96">
            <div class="relative">
                <flux:icon.magnifying-glass class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" />
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="음식명으로 검색..."
                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
            </div>
        </div>

        {{-- 페이지당 항목 수 --}}
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">표시:</label>
            <select 
                wire:model.live="perPage"
                class="px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer"
            >
                <option value="10">10개</option>
                <option value="20">20개</option>
                <option value="50">50개</option>
                <option value="100">100개</option>
            </select>
        </div>
    </div>

    {{-- 테이블 --}}
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-zinc-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors" wire:click="sortBy('name')">
                            <div class="flex items-center gap-2">
                                음식명
                                @if($sortBy === 'name')
                                    <flux:icon.chevron-up class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }} transition-transform" />
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors" wire:click="sortBy('category')">
                            <div class="flex items-center gap-2">
                                카테고리
                                @if($sortBy === 'category')
                                    <flux:icon.chevron-up class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }} transition-transform" />
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors" wire:click="sortBy('difficulty')">
                            <div class="flex items-center gap-2">
                                난이도
                                @if($sortBy === 'difficulty')
                                    <flux:icon.chevron-up class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }} transition-transform" />
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors" wire:click="sortBy('cooking_time')">
                            <div class="flex items-center gap-2">
                                조리시간
                                @if($sortBy === 'cooking_time')
                                    <flux:icon.chevron-up class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }} transition-transform" />
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            집밥 원가
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors" wire:click="sortBy('delivery_price')">
                            <div class="flex items-center gap-2">
                                배달비
                                @if($sortBy === 'delivery_price')
                                    <flux:icon.chevron-up class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }} transition-transform" />
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            절약금액
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            절약률
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            액션
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-700/50">
                    @forelse($recipes as $recipe)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $recipe->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        '한식' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
                                        '중식' => 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20',
                                        '일식' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                                        '양식' => 'bg-green-50 text-green-700 border-green-200 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/20',
                                    ];
                                    $color = $colors[$recipe->category] ?? 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-500/10 dark:text-gray-400 dark:border-gray-500/20';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium border {{ $color }}">
                                    {{ $recipe->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $recipe->difficulty_korean }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $recipe->cooking_time }}분
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ₩{{ number_format($recipe->calculateCost()) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    ₩{{ number_format($recipe->delivery_price) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                    ₩{{ number_format($recipe->calculateSavings()) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20">
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">
                                        {{ number_format($recipe->calculateSavingsPercentage(), 0) }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a 
                                    href="{{ route('recipes.show', $recipe) }}" 
                                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 dark:from-blue-500 dark:to-blue-600 dark:hover:from-blue-600 dark:hover:to-blue-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                >
                                    보기
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 mb-4 rounded-full bg-gray-100 dark:bg-zinc-700 flex items-center justify-center">
                                        <flux:icon.magnifying-glass class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">레시피를 찾을 수 없습니다</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">다른 검색어를 시도해보세요</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 페이지네이션 --}}
        @if($recipes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
</div>
