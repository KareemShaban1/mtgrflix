<section class="slider-section ">
    <div class="first-slider p-0">
        <div class="banner-slider owl-carousel owl-theme">

            @foreach (App\Models\Slider::where('is_active', 1)->get() as $item)
                <div class="item">
                    <div class="position-relative">
                        <a href="{{ $item->url }}" target="_blank">
                            <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid" alt="...">
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
