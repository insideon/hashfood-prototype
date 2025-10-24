<div class="w-full">
    <style>
        /* 페이지네이션 스타일 */
        .pagination-current {
            background-color: rgb(239 246 255) !important;
            color: rgb(29 78 216) !important;
            border-color: rgb(191 219 254) !important;
        }
        
        .pagination-link {
            background-color: rgb(255 255 255) !important;
            color: rgb(55 65 81) !important;
            border-color: rgb(229 231 235) !important;
        }
        
        .pagination-link:hover {
            background-color: rgb(249 250 251) !important;
        }
        
        .dark .pagination-current {
            background-color: rgb(63 63 70) !important;
            color: rgb(255 255 255) !important;
            border-color: rgb(82 82 91) !important;
        }
        
        .dark .pagination-link {
            background-color: rgb(39 39 42) !important;
            color: rgb(209 213 219) !important;
            border-color: rgb(63 63 70) !important;
        }
        
        .dark .pagination-link:hover {
            background-color: rgb(63 63 70) !important;
        }
    </style>
    
    {{-- 검색 및 도구 모음 --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
        {{-- 검색 --}}
        <div class="w-full sm:w-96">
            <div class="relative">
                <flux:icon.magnifying-glass class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" />
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="어떤 음식을 찾으세요?"
                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
            </div>
        </div>

        {{-- 페이지당 항목 수 --}}
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">표시:</label>
            <select 
                wire:model.live="perPage"
                class="pl-3 pr-7 py-2 text-sm bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 20 20%22%3E%3Cpath stroke=%22%236b7280%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%221.5%22 d=%22m6 8 4 4 4-4%22/%3E%3C/svg%3E')] bg-[length:0.875rem] bg-[right_0.375rem_center] bg-no-repeat"
            >
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
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
                                    '한식' => 'text-red-600 dark:text-red-400',
                                    '중식' => 'text-orange-600 dark:text-orange-400',
                                    '일식' => 'text-blue-600 dark:text-blue-400',
                                    '양식' => 'text-green-600 dark:text-green-400',
                                ];
                                $color = $colors[$recipe->category] ?? 'text-gray-600 dark:text-gray-400';
                            @endphp
                            <div class="text-sm font-medium {{ $color }}">
                                {{ $recipe->category }}
                            </div>
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
                            <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                {{ number_format($recipe->calculateSavingsPercentage(), 0) }}%
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a
                                href="{{ route('recipes.show', $recipe) }}"
                                class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition-colors duration-200"
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
