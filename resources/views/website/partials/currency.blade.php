@php
    $currency = session('currency', 'SAR');
    $symbol = session('symbol');
@endphp

@if ($currentCurrency === 'SAR')
    <img src="{{ asset('frontend/assets/image/ryal.png') }}" class="logo-product" alt="SAR" >
@else
<span class="text-primary d-inline" id="currencySymbol">{{ $symbol }}</span>

@endif
