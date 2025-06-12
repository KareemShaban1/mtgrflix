@extends('website.layout.master')

@section('title')
    الرئيسية
@endsection


@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--start Featured product-->
            <section class="py-5">
                <div class="container">
                    <div class="separator pb-4 ">
                        <h6 class="mb-0 fw-bold separator-title"> {{ $category->name }} </h6>
                    </div>

                    <div class="product-grid">
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 g-sm-4  product-ar">

                            @foreach ($category->products as $product)
                               @include('website.partials.product')
                            @endforeach

                        </div>
                        <!--end row-->
                    </div>
                </div>
            </section>
            <!--end Featured product-->


            <!--start testimonial-->
            <section class="py-5">
                <div class="container">
                    <div class="pb-4 text-center d-flex justify-content-between all-clints">
                        <a href="{{ route('testimonial') }}" class="text-white  ">
                            <div class="d-flex justify-content-center">
                                <button class="animated-btn-clint">
                                    <!-- <i class='bx bx-arrow-back fs-5 text-white me-2'></i>	 -->
                                    {{ __('site.all_clients') }}
                                </button>
                            </div>
                        </a>

                        <h5 class="mb-0 fw-bold text-uppercase pt-2">{{ __('site.testimonials') }} </h5>
                    </div>

                    <div class="product-grid">
                        <div class="latest-news owl-carousel owl-theme">

                            @foreach (App\Models\Testimonial::where('is_active', 1)->take(20)->get() as $testimonial)
                                <div class="item item-client bg-black rounded rounded-4">
                                    <div class="rounded-0  d-flex justify-content-between">
                                        <div class="f ms-4 py-3">
                                            <img src="{{ asset('frontend/assets/image/11.png') }}" class="w-100"
                                                alt="">
                                        </div>
                                        <div class="f d-flex align-items-center gap-2">
                                            <div class="py-4 name-rate">
                                                <h6 class="mb-1 text-white">
                                                    {{ $testimonial->user->name ?? $testimonial->client_name }}
                                                </h6>
                                                <div class="cursor-pointer rating">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i
                                                            class="bx bxs-star {{ $i < $testimonial->rate ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="me-2 img-review">
                                                <img src="{{ $testimonial->user?->image_url }}"
                                                    class="rounded-circle" alt=""
                                                    style="width: 50px; height: 50px;">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-white me-2 ms-2 text-clint"> {{ $testimonial->comment }} </p>
                                </div>
                            @endforeach


                        </div>
                    </div>
                </div>
            </section>
            <!--end testimonial-->

        </div>
    </div>
    <!--end page wrapper -->
@endsection
