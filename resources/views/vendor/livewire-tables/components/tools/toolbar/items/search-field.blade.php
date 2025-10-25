@aware(['isTailwind', 'isBootstrap'])

<div class="relative w-full max-w-xs">
    @if($this->hasSearchIcon)
        <x-livewire-tables::tools.toolbar.items.search.icon :searchIcon="$this->getSearchIcon" :searchIconClasses="$this->getSearchIconClasses" :searchIconOtherAttributes="$this->getSearchIconOtherAttributes"  />
    @endif

    <x-livewire-tables::tools.toolbar.items.search.input />

    {{-- ClearSearch 버튼 활성화 --}}
    @if ($this->hasSearch)
        <x-livewire-tables::tools.toolbar.items.search.remove />
    @endif
</div>
