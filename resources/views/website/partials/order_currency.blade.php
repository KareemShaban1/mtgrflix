

@if ($order->currency?->code === 'SAR')
    <img src="{{ asset('frontend/assets/image/ryal.png') }}" class="logo-product" alt="SAR">
@else
<span class="text-primary d-inline" id="currencySymbol">{{ $order->currency?->symbol }}</span>

@endif
