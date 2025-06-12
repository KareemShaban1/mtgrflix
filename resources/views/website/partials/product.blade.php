<div class="col">
    <div class="card">
        <div class="position-relative overflow-hidden">
            <a href="{{ route('product', ['productSlug' => $product->slug, 'productId' => $product->identifier]) }}">
                <img src="{{ asset('storage/' . $product->images) }}" class="img-fluid" alt="...">
            </a>
        </div>

        <div class="card-body px-0 text-center">
            <h6 class="mb-0 fw-bold product-short-title">{{ $product->name }}</h6>
            <h6 class="fw-bold product-short-title" style="font-size: 12px; color: grey; margin-top: 13px;">
                {{ $product->sub_title }}
            </h6>
            {{-- Pricing --}}
            @include('website.partials.product_price')

            {{-- Rating --}}
            @php
                $rating = $product->rating ?? 0;
                $rating = min(5, max(0, round($rating)));
            @endphp

            <div class="cursor-pointer rating mb-2 m-auto">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bx bxs-star{{ $i <= $rating ? ' text-warning' : ' text-muted' }}"></i>
                @endfor
            </div>

            {{-- Add to cart --}}
            <a href="{{ route('add_to_cart', $product->id) }}" class="text-white">
                <div class="d-flex justify-content-center">
                    <button class="animated-btn">
                        {{ __('site.add_cart') }} <i class='bx bx-cart-add fs-5 text-white me-2'></i>
                    </button>
                </div>
            </a>
        </div>

    </div>
</div>
