@props(['columnTitle' => '', 'customLabelAttributes' => ['default' => true]])
@if($columnTitle === '집밥 원가')
    <span {{ $customLabelAttributes->except(['default', 'default-colors', 'default-styling']) }}>
        <span class="cooking-cost-label">{{ $columnTitle }}</span>
    </span>
@elseif($columnTitle === '배달 가격')
    <span {{ $customLabelAttributes->except(['default', 'default-colors', 'default-styling']) }}>
        <span class="delivery-price-label">{{ $columnTitle }}</span>
    </span>
@else
    <span {{ $customLabelAttributes->except(['default', 'default-colors', 'default-styling']) }}>
        {{ $columnTitle }}
    </span>
@endif
