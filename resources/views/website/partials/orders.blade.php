@foreach ($orders as $order)
    <tr>
        <td class="pt-4">
            @if ($order->status_id == 3 || ($order->status = 'تم التنفيذ'))
                <a href="{{ route('order.details', $order->number) }}">
                    <p class="text-dark">#{{ $order->number }}</p>
                </a>
            @else
                <p class="text-dark">#{{ $order->number }}</p>
            @endif
        </td>

        <td class="pt-4">{{ $order->grand_total * $order->exchange_rate }}
            @include('website.partials.order_currency', ['order' => $order])</td>
        <td class="pt-4">{{ $order->created_at }}</td>
        <td>
            <div class="d-flex">
                @if ($order->statusId)
                    <div
                        class="d-flex border rounded-pill my-1 ps-3 pe-3 m-2 
                @if ($order->statusId->id == 1) border-warning text-warning
                @elseif($order->statusId->id == 2) border-info text-info
                @elseif($order->statusId->id == 3) border-success text-success
                @elseif($order->statusId->id == 4) border-danger text-danger @endif">
                        <i
                            class='bx fs-5 custom-check-icon my-2 
                    @if ($order->statusId->id == 1) bx-time text-warning
                    @elseif($order->statusId->id == 2) bx-truck text-info
                    @elseif($order->statusId->id == 3) bx-check text-success
                    @elseif($order->statusId->id == 4) bx-x text-danger @endif'></i>
                        <div class="my-2">
                            {{ $order->statusId->name }}
                        </div>
                    </div>
                @else
                    <div class="d-flex border rounded-pill my-1 ps-3 pe-3 m-2 border-secondary border-success text-success">
                        <i class="bx fs-5 custom-check-icon my-2 bx-check border-success text-success"></i>
                        <div class="my-2">
                            {{ $order->status }}
                        </div>
                    </div>
                @endif

                @if ($order->status_id == 3 && $order->reviews->isEmpty())
                    <div class="d-flex border rounded-pill my-1 ps-3 pe-3 border-warning">
                        <a href="#" data-bs-toggle="modal"
                            data-bs-target="#modal-rate-product-{{ $order->id }}"
                            data-order-id="{{ $order->id }}">
                            <p class="my-2 text-warning">
                                {{ __('site.rate') }}</p>
                        </a>
                    </div>
                @endif
            </div>
        </td>

    </tr>

    @if (isset($order))
        <div class="modal fade" id="modal-rate-product-{{ $order->id }}" tabindex="-1" style="display: none;">
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

                                <div id="product-stars" class="stars-container my-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}">&#9733;</span>
                                    @endfor
                                </div>

                                <input type="hidden" class="order_id" value="{{ $order->id }}">
                                <input type="hidden" class="product_id" value="{{ $item->product->id }}">

                                <p id="rating-text" class="rating-text"></p>

                                <textarea class="form-control feedback " placeholder="{{ __('site.share_your_feedback') }}" rows="3"></textarea>
                            </div>
                        @endforeach

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary submit-rating">{{ __('site.rate_product') }}</button>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endforeach
