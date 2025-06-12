<div class="d-flex align-items-center flex-row-reverse gap-1 font-13">
    <p class="mb-0 pt-1 fw-bold" style="margin-right: 5px;">
        ({{ number_format($reviewsCount) }} {{ $reviewsCount == 1 ? __('site.one_review') : __('site.reviews') }})
    </p>

    <div class="rates cursor-pointer d-flex">
        @for ($i = 1; $i <= 5; $i++)
            @if ($rating >= $i)
                <i class="bx bxs-star text-warning"></i>
            @elseif ($rating >= $i - 0.5)
                <i class="bx bxs-star-half text-warning"></i>
            @else
                <i class="bx bx-star text-warning"></i>
            @endif
        @endfor
    </div>
</div>
