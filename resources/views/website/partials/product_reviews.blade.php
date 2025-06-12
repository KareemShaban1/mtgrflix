@foreach ($reviews as $review)
    <div>
        <div class="buy-review my-1 d-flex pt-2">

            <div class="d-flex justify-content-center">
                <div class="review-user pb-3 me-1">
                    <img src="{{ asset('frontend/assets/image/avatar_male.webp') }}" width="65" height="65"
                        class="rounded-circle" alt="{{ __('site.user_avatar') }}" />
                </div>
            </div>

            <div class="review-content me-1">
                <div class="pt-1">
                    <h5>{{ $review->user?->name ?? $review->client_name }}</h5>
                </div>

                <div class="d-flex align-items-center pb-3">
                    <h6 class="pt-1 ms-0" style="font-size: 12px;">
                        <img src="{{ asset('frontend/assets/image/right.png') }}" class="rating-icone-right"
                            alt="verified">
                        {{ __('site.purchased_and_rated') }}
                    </h6>
                    <div class="rates cursor-pointer d-flex me-1">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="bx bxs-star {{ $i < $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                </div>

                <h6 class="fw-bold text-dark">{{ $review->review }}</h6>
            </div>

           

            <p class="text-dark pt-2" style="font-size: 10px;">
                {{ $review->created_at->diffForHumans() }}
                    {{-- {{ $review->created_at }} --}}

            </p>



        </div>
    </div>
    <hr>
@endforeach
