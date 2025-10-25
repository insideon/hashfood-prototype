@props(['columnTitle' => '', 'customLabelAttributes' => ['default' => true]])
<span {{ $customLabelAttributes->except(['default', 'default-colors', 'default-styling']) }} class="overflow-hidden text-ellipsis whitespace-nowrap">
    {{ $columnTitle }}
</span>
