<div class="w-full">
    {{-- 검색 및 도구 모음 --}}
    <div class="mb-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
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
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-2 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg">
                <flux:icon.list-bullet class="w-4 h-4 text-gray-400 dark:text-gray-500" />
                <select
                    wire:model.live="perPage"
                    class="text-sm bg-transparent text-gray-900 dark:text-white focus:outline-none cursor-pointer"
                >
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    {{-- 테이블 --}}
    <div class="overflow-x-auto">
        {{ $this->table }}
    </div>

    {{-- 페이지네이션 --}}
    {{ $this->paginationView }}
</div>
