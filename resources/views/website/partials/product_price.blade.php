<div class="product-price d-flex align-items-center justify-content-center gap-2 mt-2">
    @php
        $rate = session('rate', 1);
    @endphp

    @if ($product->promotional_price && $product->promotional_price < $product->price)
        <!-- Original price with strikethrough -->
        <div class="d-flex align-items-center justify-content-center">
            <div class="h6 pt-1 text-secondary text-decoration-line-through small-price">
                {{ number_format($product->price * $rate, 2) }}
            </div>
            @include('website.partials.product_currency')
            {{-- <img src="{{ asset('frontend/assets/image/ryal.png') }}" class="images-like-small" alt="SAR" style="width: 16px; height: 16px; margin-left: 4px;"> --}}
        </div>

        <!-- Promotional price -->
        <div class="d-flex align-items-center justify-content-center">
            <div class="h6 fw-bold text-danger">
                {{ number_format($product->promotional_price * $rate, 2) }}
            </div>
            @include('website.partials.product_currency')
            {{-- <img src="{{ asset('frontend/assets/image/ryal1.png') }}" class="images-like" alt="SAR" style="width: 18px; height: 18px; margin-left: 4px;"> --}}
        </div>
    @else
        <!-- Regular price -->
        <div class="d-flex align-items-center justify-content-center">
            <div class="h6 fw-bold text-dark">
                {{ number_format($product->price * $rate, 2) }}
            </div>
            @include('website.partials.product_currency')
        </div>
    @endif
</div>
