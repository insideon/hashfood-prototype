@aware(['isTailwind', 'isBootstrap'])

<div class="relative w-full sm:w-96 mb-4">
    @if($this->hasSearchIcon)
        <x-livewire-tables::tools.toolbar.items.search.icon :searchIcon="$this->getSearchIcon" :searchIconClasses="$this->getSearchIconClasses" :searchIconOtherAttributes="$this->getSearchIconOtherAttributes"  />
    @endif

    <x-livewire-tables::tools.toolbar.items.search.input />

    @if ($this->hasSearch)
        <x-livewire-tables::tools.toolbar.items.search.remove />
    @endif
</div>
