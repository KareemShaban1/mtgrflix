@foreach ($orders as $order)
    <div class="py-4 border-bottom">
        <div class="d-flex justify-content-between">
            @if ($order->status_id == 3)
                <a href="{{ route('order.details', $order->number) }}">
                    <p style="color: #76787b;">{{ __('site.order_number') }}</p>
                </a>
            @else
                <p style="color: #76787b;">{{ __('site.order_number') }}</p>
            @endif
            <h6>
                <button onclick="copyToClipboard('{{ $order->number }}')" class="btn ms-2">
                    <i class="fas fa-copy text-success"></i>
                </button>
                @if ($order->status_id == 3)
                    <a href="{{ route('order.details', $order->number) }}">{{ $order->number }}</a>
                @else
                    {{ $order->number }}
                @endif
            </h6>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <p>{{ __('site.total') }}</p>
            <h6>{{ $order->grand_total * $order->exchange_rate }} @include('website.partials.order_currency', ['order' => $order])</h6>
        </div>

        <div class="d-flex justify-content-between">
            <p>{{ __('site.date') }}</p>
            <h6>{{ $order->created_at->translatedFormat('l d F Y') }}</h6>
        </div>

       <div class="d-flex justify-content-between">
    <p class="pt-3">{{ __('site.status') }}</p>
    <div class="d-flex">
        @php
            $statusId = $order->status_id;
            $color = match ($statusId) {
                1 => 'secondary', // Pending
                2 => 'info',      // Shipped
                3 => 'success',   // Completed
                4 => 'danger',    // Cancelled
                5 => 'warning',   // Declined
                default => 'dark',
            };
            $statusName = $order->statusId?->name ?? $order->status;
        @endphp

        <div class="d-flex justify-content-center align-items-center border rounded-pill my-1 border-{{ $color }} ms-1 small-box px-2"
            style="white-space: nowrap; width: auto;">
            @if ($statusId === 3)
                <i class='bx bx-check fs-5 text-{{ $color }} me-1'></i>
            @endif
            <div class="text-{{ $color }}">{{ $statusName }}</div>
        </div>

        @if ($order->status_id == 3 && $order->reviews->isEmpty())
            <div class="d-flex border rounded-pill my-1 ps-3 pe-3 border-warning">
                <a href="#" data-bs-toggle="modal"
                    data-bs-target="#modal2-rate-product-{{ $order->id }}" data-id="{{ $order->id }}">
                    <p class="my-2 text-warning">{{ __('site.rate') }}</p>
                </a>
            </div>
        @endif
    </div>
</div>

    </div>


    @if (isset($order))
        <div class="modal fade" id="modal2-rate-product-{{ $order->id }}" tabindex="-1" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('site.rate_order') }} (#{{ $order->number }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">

                        @foreach ($order->items as $index => $item)
                            <div class="product-slide" data-index="{{ $index }}"
                                style="{{ $index != 0 ? 'display:none;' : '' }}">
                                <img src="{{ asset('storage/' . $item->product->images) }}" class="w-25 mb-2"
                                    alt="{{ __('site.product_image') }}">
                                <h6>{{ $item->product->name }}</h6>

                                <div class="product-stars" class="stars-container my-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}">&#9733;</span>
                                    @endfor
                                </div>

                                <input type="hidden" class="order_id" value="{{ $order->id }}">
                                <input type="hidden" class="product_id" value="{{ $item->product->id }}">

                                <p class="rating-text" class="rating-text"></p>

                                <textarea class="form-control feedback" placeholder="{{ __('site.share_your_feedback') }}" rows="3"></textarea>
                            </div>
                        @endforeach

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary submit-rating-2">{{ __('site.rate_product') }}</button>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endforeach
