<div>
    <button 
        wire:click="toggle"
        class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors duration-200"
        title="{{ $isDark ? '라이트 모드로 변경' : '다크 모드로 변경' }}"
    >
        @if($isDark)
            <flux:icon.sun class="w-5 h-5" />
        @else
            <flux:icon.moon class="w-5 h-5" />
        @endif
    </button>
</div>