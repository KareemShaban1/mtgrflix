@php
    $currency = session('currency', 'SAR');
    $symbol = session('symbol');
@endphp

@if ($currentCurrency === 'SAR')
    <img src="{{ asset('frontend/assets/image/ryal1.png') }}" class="images-like" alt="SAR"
        style="width: 18px; height: 18px; margin-right: 4px; margin-bottom: 4px;">
@else
    <span class="text-primary d-inline "  style="margin-right: 2px; margin-bottom: 6px;" id="currencySymbol">{{ $symbol }}</span>
@endif
