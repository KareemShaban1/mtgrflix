<div class="product-price d-flex align-items-center justify-content-center gap-2 mt-2">
  @php
      $rate = session('rate', 1);
  @endphp

    @if ($product->promotional_price && $product->promotional_price < $product->price)
        <!-- Original price with strikethrough -->
        <div class="{{ $class1 }}">
            {{ number_format($product->price * $rate, 2) }}
            @include('website.partials.product_currency')
        </div>

        <!-- Promotional price -->
        <div class="{{ $class2 }}">
            {{ number_format($product->promotional_price * $rate, 2) }}
            @include('website.partials.product_currency')

        </div>
    @else
        <!-- Regular price -->
        <div class="{{ $class3 }}">
            {{ number_format($product->price* $rate, 2) }}
            @include('website.partials.product_currency')

        </div>
    @endif
</div>
