@aware(['tableName','isTailwind','isBootstrap'])
@props(['colCount' => 1])

@php
    $loaderRow = $this->getLoadingPlaceHolderRowAttributes();
    $loaderCell = $this->getLoadingPlaceHolderCellAttributes();
    $loaderIcon = $this->getLoadingPlaceHolderIconAttributes();
@endphp

<tr wire:key="{{ $tableName }}-loader" wire:loading.class.remove="hidden d-none" {{
    $attributes->merge($loaderRow)
        ->class([
            'hidden w-full text-center place-items-center align-middle' => $isTailwind && ($loaderRow['default'] ?? true),
            'd-none w-100 text-center align-items-center' => $isBootstrap && ($loaderRow['default'] ?? true),
        ])
        ->except(['default','default-styling','default-colors'])
}}>
    <td colspan="{{ $colCount }}" wire:key="{{ $tableName }}-loader-column" {{
        $attributes->merge($loaderCell)
            ->class([
                'py-4' => $isTailwind && ($loaderCell['default'] ?? true),
                'py-4' => $isBootstrap && ($loaderCell['default'] ?? true),
            ])
            ->except(['default','default-styling','default-colors', 'colspan','wire:key'])
    }}>
        @if($this->hasLoadingPlaceholderBlade())
            @include($this->getLoadingPlaceHolderBlade(), ['colCount' => $colCount])
        @else
            <div class="flex flex-col items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 dark:border-blue-400 mb-4"></div>
                <div class="text-base font-medium text-zinc-700 dark:text-zinc-300">{!! $this->getLoadingPlaceholderContent() !!}</div>
            </div>
        @endif
    </td>
</tr>
