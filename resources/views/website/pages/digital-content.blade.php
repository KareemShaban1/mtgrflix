@extends('website.layout.master')

@section('title')
    {{ __('site.home') }}
@endsection

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--start shop cart-->
            <section class="py-1 notification">
                <div class="container">
                    <h3 class="d-none">{{ __('site.orders') }}</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 d-lg-block">
                                    <div class="card-body mb-2">
                                        <div class="card mb-0">
                                            <div class="">
                                                <div class="card-body py-4 border mb-2 border-bottom mt-3 rounded">
                                                    <div class="col-12 col-lg-6 d-flex flex-column flex-lg-row align-items-center gap-3">
                                                        <div class="cart-img text-center">
                                                            <img src="{{ asset('frontend') }}/assets/image/logo.avif"
                                                                width="100" alt="">
                                                        </div>

                                                        <div class="cart-detail text-center pt-4">
                                                            <h5 class="fw-bold">
                                                                {{ __('site.welcome_message', ['name' => $order->user->first_name]) }}
                                                            </h5>
                                                            <div class="d-flex" style="font-size: 14px;">
                                                                <a href="{{ route('home') }}"
                                                                    style="color: #7e8284;">{{ __('site.store') }}</a>
                                                                <p>/</p>
                                                                <a href=""
                                                                    style="color: #7e8284;">{{ __('site.cart') }}</a>
                                                                <p>/</p>
                                                                <a href=""
                                                                    style="color: #7e8284;">{{ __('site.order_summary') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body py-4 border mb-2 border-bottom mt-3">
                                                    <div class="col-12 d-flex justify-content-between gap-3">
                                                        <div class="d-flex">
                                                            <div class="cart-img text-center ms-2">
                                                                <img src="{{ asset('frontend') }}/assets/image/check.png"
                                                                    class="pt-3" alt="" style="width: 40px;">
                                                            </div>

                                                            <div class="cart-detail text-center pt-4">
                                                                <a href="{{ route('my-orders') }}">
                                                                    <h6 class="mb-2">{{ __('site.order_number') }} <p
                                                                            class="text-primary">#{{ $order->number }}</p>
                                                                    </h6>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="cart-detail text-center pt-4">
                                                            <h6 class="ms-2 text-primary"><i class='bx bx-code-block'></i>
                                                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                    <div class="cart-detail text-center pt-4">
                                                        <h4 class="pb-3 fw-bold">{{ __('filament.thank_you_message') }}</h4>
                                                        <h5 class="pb-3"> <img
                                                                src="{{ asset('frontend') }}/assets/image/right.png"
                                                                class="rating-icone-right-digital"
                                                                alt="">{{ __('filament.order_info_whatsapp') }}<br> <i
                                                                class="fab fa-whatsapp text-success fa-2x pt-2"></i> </h5>
                                                        <h5>{{ __('filament.view_order_details_here') }}</h5>
                                                    </div>
                                                </div>
                                                <div class="card-body py-4 border mb-2 border-bottom mt-3">
                                                    <h5>{{ __('site.products') }}</h5>
                                                    @if ($order->items->count() > 0)
                                                        @foreach ($order->items as $item)
                                                            <div class="col-12 col-lg-6 d-flex gap-3">
                                                                <div class="cart-img text-center">
                                                                    <img src="{{ asset('storage/' . $item->product->images) }}"
                                                                        width="100" alt="">
                                                                </div>
                                                                <div class="cart-detail text-center pt-4">
                                                                    <h6>{{ $item->product->name }}</h6>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                </div>

                                                <div class="card-body py-4 mb-2 d-flex border flex-column flex-md-row justify-content-between align-items-center align-items-md-start @if(app()->getLocale() == 'ar') flex-row-reverse @endif">
                                                    @php
                                                        $codeToDisplay = null;
                                                        foreach ($order->items ?? [] as $item) {
                                                            if (isset($item->selectedCode)) {
                                                                $codeToDisplay = $item->selectedCode->code;
                                                                break;
                                                            }
                                                        }
                                                    @endphp

                                                    @if($codeToDisplay)
                                                        <div class="flex-grow-1 me-md-3 ms-md-0 text-{{ app()->getLocale() == 'ar' ? 'end' : 'start' }}" style="white-space: pre-line;" id="code-to-copy">
                                                            {!! $codeToDisplay !!}
                                                        </div>

                                                        <div class="border rounded-2 pt-1 px-2 border-light-subtle mt-3 mt-md-0"
                                                            style="width: 100px; height: 50px; background-color: rgb(51, 138, 245);">
                                                            <button onclick="copyCode()" class="btn text-dark"
                                                                style="width: 100%; height: 100%; background-color: rgb(51, 138, 245);">{{ __('site.copy') }}</button>
                                                        </div>
                                                    @else
                                                        <div class="text-muted">{{ __('site.no_codes_available') }}</div>
                                                    @endif
                                                </div>
                                                @endif
                                                <div class="card-body py-4 border mb-2 border-bottom m-auto text-center">
                                                    <div class="cart-img pb-2">
                                                        <h5> {{ __('filament.support') }}<i
                                                                class="fab fa-whatsapp text-success fa-2x pt-2"></i> </h5>
                                                    </div>

                                                    <div class="cart-detail">
                                                        <h6 class="text-white"><a href="https://wa.me/966551200896"
                                                                class="text-primary"> <i class="fab fa-whatsapp"></i>
                                                                966551200896+</a></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--end shop cart-->
        </div>
    </div>
    <!--end page wrapper -->
@endsection
