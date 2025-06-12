@if ($order)
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

                            <textarea class="form-control feedback" placeholder="{{ __('site.share_your_feedback') }}" rows="3"></textarea>
                        </div>
                    @endforeach

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary submit-rating">{{ __('site.rate_store') }}</button>
                </div>

            </div>
        </div>
    </div>
@endif


<!-- modal ecom-->
<!-- Rate Store Modal -->
<div class="modal fade" id="modal-rate-store" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('site.rate_order') }} (#{{ $order->number }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="product-image" src="{{ asset('frontend') }}/assets/image/logo.avif" class="w-25 mb-2"
                    alt="{{ __('site.product_image') }}">
                <h6>{{ __('site.how_was_your_experience_with_store') }}</h6>

                <div id="store-stars" class="stars-container my-2">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                </div>

                <p id="store-rating-text" class="rating-text"></p>

                <textarea class="form-control" id="store-review" placeholder="{{ __('site.share_your_feedback') }}" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary submit-store-rating">{{ __('site.submit_rating') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Thank You Modal -->
<div class="modal fade" id="modal-thank-you" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('site.rate_order') }} (#{{ $order->number }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fa-regular fa-circle-check fa-3x text-primary my-4"></i>
                <h5 class="pb-3">{{ __('site.thank_you_for_rating') }}</h5>
                <p class="pb-3">{{ __('site.thank_you_for_your_time') }} 💙 <br>
                    {{ __('site.discount_code_for_next_order') }} </p>

                <p id="countdown" class="pb-3"> <span id="time-left">00:30</span></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="final-thankyou"
                    data-bs-dismiss="modal">{{ __('site.close') }}</button>
            </div>
        </div>
    </div>
</div>
