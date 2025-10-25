@aware(['isTailwind', 'isBootstrap'])

<button
    wire:click="clearSearch"
    type="button"
    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors"
    aria-label="Clear search"
>
    <x-heroicon-m-x-mark class="w-4 h-4 sm:w-5 sm:h-5" />
</button>
