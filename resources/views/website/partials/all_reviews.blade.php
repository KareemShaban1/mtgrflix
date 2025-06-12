@foreach ($reviews as $review)
<div class="card-body py-4 mb-2 border-bottom mt-3 d-flex justify-content-between">
    <div class="d-flex" style="width: 80%;">
        <div class="me-2">
            <img src="{{ asset('frontend/assets/image/avatar_male.webp') }}" class="rounded-circle" alt="" style="width: 50px; height: 50px;">
        </div>
        <div class="pt-2">
            <h6>{{ $review->user->name ?? $review->client_name }}</h6>
            <div class="cursor-pointer rating">
                @for ($i = 0; $i < 5; $i++)
                    <i class="bx bxs-star {{ $i < $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                @endfor
            </div> 
            <h6>{{ $review->review }}</h6>
        </div>
    </div>	
    <div class="d-flex gap-3">
        <p>{{ $review->created_at->diffForHumans() }}</p>
    </div>	
</div>
@endforeach