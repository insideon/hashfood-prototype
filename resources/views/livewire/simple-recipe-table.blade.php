<div class="w-full">
    <style>
        /* 페이지네이션 다크모드 색상 오버라이드 */
        .dark nav[role="navigation"] button,
        .dark nav[role="navigation"] span span {
            background-color: rgb(39 39 42);
            color: rgb(209 213 219);
        }

        .dark nav[role="navigation"] span[aria-current="page"] span {
            background-color: rgb(59 130 246);
            color: rgb(255 255 255);
        }

        /* 페이지네이션 텍스트 숨기기 */
        nav[role="navigation"] p {
            display: none;
        }

        /* 테이블 헤더 스타일 */
        .sortable-header {
            cursor: pointer;
            user-select: none;
        }

        .sortable-header:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dark .sortable-header:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>

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
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            {{ $this->table }}
        </div>
    </div>

    {{-- 페이지네이션 --}}
    <div class="flex justify-center mt-6">
        {{ $this->paginationView }}
    </div>
</div>
