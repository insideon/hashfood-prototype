
@aware(['isTailwind', 'isBootstrap'])

<div @class([
        'd-inline-flex h-100 align-items-center ' => $isBootstrap,
    ])>
    <div
        wire:click="clearSearch"
        @class([
            'btn btn-outline-secondary d-inline-flex h-100 align-items-center' => $isBootstrap,
            'absolute right-3 top-1/2 transform -translate-y-1/2 w-6 h-6 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-pointer transition-colors' => $isTailwind,
        ])
        title="검색어 지우기"
    >
        @if($isTailwind)
        <x-heroicon-m-x-mark class='w-4 h-4' />
        @else
        <x-heroicon-m-x-mark class="laravel-livewire-tables-btn-smaller" />
        @endif
    </div>
</div>
