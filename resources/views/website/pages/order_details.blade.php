@extends('website.layout.master')

@section('title')
    الرئيسية
@endsection
@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!--start page wrapper -->
            <div class="page-wrapper">
                <div class="page-content">

                    <!--start breadcrumb-->
                    <section class="py-3 border-bottom border-top  d-md-flex bg-light">
                        <div class="container">
                            <div class="page-breadcrumb d-flex align-items-center">
                                <div class="ms-auto">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 p-0">
                                            <i class='bx bx-chevron-left'></i>

                                            <li class="breadcrumb-item active" aria-current="page">
                                                <a href="{{ route('my-orders') }}">
                                                    {{ __('site.orders') }}
                                                </a>
                                            </li>
                                            <i class='bx bx-chevron-left'></i>

                                            <li class="breadcrumb-item">
                                                <a href="{{ route('my-profile') }}">
                                                    {{ __('site.my_account') }}
                                                </a>
                                            </li>
                                            <i class='bx bx-chevron-left'></i>

                                            <li class="breadcrumb-item">
                                                <a href="{{ route('home') }}">
                                                    {{ __('site.home') }}
                                                </a>
                                            </li>
                                        </ol>

                                    </nav>
                                </div>

                            </div>
                        </div>
                    </section>
                    <!--end breadcrumb-->
                </div>
            </div>
            <!--end page wrapper -->



            <!--start shop cart-->
            <section class="py-1 notification">
                <div class="container">
                    <h3 class="d-none">{{ __('site.my_account') }}</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                @include('website.partials.user-menu')



                                <div class="col-lg-8 d-lg-block">
                                    <div class="d-flex justify-content-between">
                                        <div class="mb-0 pt-3">
                                            <h4 class="d-block fw-bold py-0 fs-6">
                                                {{ __('site.Order Details') }} #{{ $order->number }}
                                            </h4>
                                        </div>
                                        @if ($order->status_id == 3)
                                            <div class="mb-0">
                                                <a href="{{ route('digital.content', $order->id) }}" class="text-dark">
                                                    <h6 class="d-block text-primary py-1">
                                                        {{ __('site.Digital Content') }}
                                                        <i class='bx bx-arrow-from-bottom fs-5 text-primary my-2'></i>
                                                    </h6>
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="card-body mb-2">
                                        <div class="card mb-0">
                                            <div class="card-body border mb-2 border-bottom">
                                                <div class="d-flex flex-column flex-md-row justify-content-between">
                                                    <div>
                                                        <p class="mb-0">{{ __('site.Order Date') }}</p>
                                                        <a href="">
                                                            <p class="text-dark fw-bold">
                                                                {{ $order->created_at->translatedFormat('j F Y') }}</p>
                                                        </a>
                                                    </div>

                                                    <div class="d-flex flex-row gap-2">
                                                        <!-- Status box -->
                                                        <div class="d-flex align-items-center border rounded-pill my-1 small-box border-success px-4 "
                                                            style="white-space: nowrap; width: fit-content;">
                                                            <i class='bx bx-check fs-5 custom-check-icon text-success'></i>
                                                            <div class="text-success ms-2">{{ $order->statusId?->name }}
                                                            </div>
                                                        </div>
                                                        @if ($order->status_id == 3)
                                                            <!-- Download invoice box -->
                                                            <div
                                                                class="border rounded-2 my-1 px-3 border-primary bg-primary d-flex align-items-center">
                                                                <a href="{{ route('invoice.download', $order->id) }}"
                                                                    class="text-white text-decoration-none d-flex align-items-center">
                                                                    <span
                                                                        class="me-1">{{ __('site.Download Invoice') }}</span>
                                                                    <i class='bx bx-download fs-5 text-white'></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- Product Rating Section --}}
                                            <div class="card-body py-4 bg-primary">
                                                <div class="d-flex justify-content-between my-2">
                                                    <div class="order" style="width: 75%;">
                                                        <h2 class="mb-0">{{ __('site.Product Rating') }}</h2>
                                                        <p class="text-dark pt-2">
                                                            {{ __('site.Rate the product and store to get a discount code for your next order 🤩') }}
                                                        </p>
                                                    </div>
                                                    @if ($order->status_id == 3 && $order->reviews->isEmpty())
                                                        <div class="border rounded-2 py-1 px-2 border-light-subtle small-box text-center"
                                                            style="background-color: rgb(51, 138, 245); height: 60px;">
                                                            <a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#modal-rate-product-{{ $order->id }}"
                                                                data-id="{{ $order->id }}">
                                                                <p class="my-2 text-dark">{{ __('site.Rate Order') }}</p>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Check if order has items --}}
                                            @if ($order->items && $order->items->count())
                                                {{-- Products --}}
                                                @foreach ($order->items as $item)
                                                    <div class="card-body py-4 border mb-2 border-bottom mt-3">
                                                        <div class="col-12 col-lg-6 d-flex gap-3">
                                                            <div class="cart-img text-center">
                                                                <img src="{{ asset('storage/' . $item->product->images) }}"
                                                                    width="100" alt="">
                                                            </div>
                                                            <div class="cart-detail pt-4">
                                                                <h6>{{ $item->product->name }}</h6>
                                                                <h6>{{ $item->product->price }}
                                                                    @include('website.partials.currency')</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                {{-- Quantity --}}
                                                <div
                                                    class="card-body py-4 border mb-2 border-bottom d-flex justify-content-between">
                                                    <div class="cart-img text-center">
                                                        <h6>{{ __('site.Quantity') }}</h6>
                                                    </div>
                                                    <div class="cart-detail">
                                                        <h6>{{ $order->items->sum('quantity') }}</h6>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Fallback: display product_items column --}}
                                                <div class="card-body py-4 border mb-2 border-bottom mt-3">
                                                    <div class="col-12">
                                                        <div class="cart-detail pt-4">
                                                            <h6>{{ __('site.cart_summery') }}</h6>
                                                            <p>{{ $order->product_items }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            {{-- sub_total --}}
                                            <div
                                                class="card-body py-4 border mb-2 border-bottom d-flex justify-content-between">
                                                <div class="cart-img text-center">
                                                    <h6>{{ __('site.subtotal') }}</h6>
                                                </div>
                                                <div class="cart-detail">
                                                    <h6>{{ $order->sub_total * $order->exchange_rate }}
                                                        @include('website.partials.order_currency', [
                                                            'order' => $order,
                                                        ])</h6>
                                                </div>
                                            </div>

                                            {{-- Files --}}
                                            @if ($order->status_id == 3)
                                                <h6 class="pt-3">{{ __('site.Files') }}</h6>

                                                <div
                                                    class="card-body py-4 border mb-2 border-bottom d-flex justify-content-between">
                                                    <div class="cart-img text-center">
                                                        <a href="{{ route('invoice.download') }}">
                                                            <h6>{{ __('site.Preview Digital Content') }}</h6>
                                                        </a>
                                                    </div>
                                                    <div class="cart-detail">
                                                        <a href="{{ route('digital.content', $order->id) }}"
                                                            class="text-dark">
                                                            <i class='bx bx-download fs-5'></i>
                                                            {{ __('site.Download File') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($order->status_id == 3)
                                                {{-- Totals --}}
                                                <div
                                                    class="card-body py-4 border mb-2 border-bottom d-flex justify-content-between">
                                                    <div class="cart-img text-center">
                                                        <h6>{{ __('site.Total Products') }}</h6>
                                                    </div>
                                                    <div class="cart-detail">
                                                        <h6>{{ $order->sub_total * $order->exchange_rate }}
                                                            @include('website.partials.order_currency', [
                                                                'order' => $order,
                                                            ])
                                                        </h6>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($order->coupon)
                                                <div
                                                    class="card-body py-4 border mb-2 border-bottom d-flex justify-content-between">
                                                    <div class="cart-img text-center">
                                                        <h6>{{ __('site.Coupon') }} {{ $order->coupon->code }}</h6>
                                                    </div>
                                                    <div class="cart-detail">
                                                        <h6>-{{ $order->discount }}
                                                            @include('website.partials.currency', [
                                                                'order' => $order,
                                                            ])</h6>
                                                    </div>
                                                </div>
                                            @endif

                                            <div
                                                class="card-body py-4 border mb-2 border-bottom d-flex justify-content-between">
                                                <div class="cart-img text-center">
                                                    <h6>{{ __('site.Total') }}</h6>
                                                </div>
                                                <div class="cart-detail">
                                                    <h6>{{ $order->grand_total * $order->exchange_rate }}
                                                        @include('website.partials.order_currency', [
                                                            'order' => $order,
                                                        ])</h6>
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
    @include('website.partials.product-ratings')
    <!--end page wrapper -->
@endsection


@section('script')
@include('website.layout.custom-js')
    {{-- <script src="{{ asset('frontend/') }}/assets/js/custom.js"></script> --}}
@endsection
