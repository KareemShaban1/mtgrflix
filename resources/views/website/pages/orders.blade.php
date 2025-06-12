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
                                            <li class="breadcrumb-item active" aria-current="page"> {{ __('site.orders') }}
                                            </li>
                                            <i class='bx bx-chevron-left'></i>
                                            <li class="breadcrumb-item"><a href="{{ route('my-profile') }}">
                                                    {{ __('site.my_account') }}</a></li>
                                            <i class='bx bx-chevron-left'></i>

                                            <li class="breadcrumb-item"><a href="{{ route('home') }}"> {{ __('site.home') }}</a></li>
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
            <section class="py-4 notification">
                <div class="container">
                    <h3 class="d-none">{{ __('site.my_account') }}</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                @include('website.partials.user-menu')

                                <!-- small screen orders -->
                                <div class="mobil d-block d-lg-none">
                                    <div class="j border-bottom">
                                        <h5 class="text-center">{{ __('site.orders') }}</h5>
                                    </div>
                                  
                                    <div id="data-wrapper2">
                                        
                                        @include('website.partials.mobile-orders') <!-- Initial set of orders -->

                                    </div>

                                    @if ($orders->isNotEmpty())
                                        @include('website.partials.load-more', ['type' => 'mobile'])
                                    @endif
                                </div>


                                <!-- large screen orders -->
                                <div class="col-lg-8 d-none d-lg-block">
                                    <h4 class="d-block text-center py-3">{{ __('site.orders') }}</h4>
                                    <div class="card-body border mb-2">
                                        <div class="card shadow-none mb-0">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('site.order_number') }}</th>
                                                                <th>{{ __('site.total') }}</th>
                                                                <th>{{ __('site.date') }}</th>
                                                                <th class="text-center">{{ __('site.status') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tbody id="data-wrapper">
                                                            @include('website.partials.orders') <!-- Initial set of orders -->
                                                        </tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($orders->isNotEmpty())
                                            @include('website.partials.load-more', ['type' => 'desktop'])
                                        @endif
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
    <!-- testimonials modals start -->
    <!-- modal product -->

    <div class="modal fade" id="modal-rate-store" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('site.rate_order') }} </h5>
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
                    <h5 class="modal-title">{{ __('site.rate_order') }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fa-regular fa-circle-check fa-3x text-primary my-4"></i>
                    <h5 class="pb-3">{{ __('site.thank_you_for_rating') }}</h5>
                    <p class="pb-3">{{ __('site.thank_you_for_your_time') }} 💙 <br>
                        {{ __('site.discount_code_for_next_order') }}
                    <p id="coupon-display"></p></p>

                    <p id="countdown" class="pb-3"> <span id="time-left">00:30</span></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="final-thankyou"
                        data-bs-dismiss="modal">{{ __('site.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- testimonials modals end -->
    <!--end page wrapper -->
@endsection

@section('script')
@include('website.layout.custom-js')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // toast.success('Copied to clipboard');
                toastr.success('Copied to clipboard: ' + text);
            }).catch(function(err) {
                toastr.error('Failed to copy: ' + err);
            });
        }
    </script>

    <script>
        var ENDPOINT = "{{ route('my-orders') }}";
        var page = 1;
        console.log(document.getElementById('data-wrapper2'));

        // Handle load more for both desktop and mobile
        $(document).on('click', '.load-more-data', function() {
            var button = $(this);
            var type = button.data('type'); // Get the view type from data attribute
            page++;
            infinteLoadMore(page, type);
        });

        function infinteLoadMore(page, type) {
            $.ajax({
                    url: ENDPOINT + "?page=" + page + "&type=" + type,
                    datatype: "json",
                    type: "get",
                    beforeSend: function() {
                        $('.auto-load').show();
                        $('.load-more-data').prop('disabled', true);
                    }
                })
                .done(function(response) {
                    $('.auto-load').hide();
                    $('.load-more-data').prop('disabled', false);

                    // Append to the correct container based on type
                    if (type === 'mobile' && response.mobile_html) {
                        
                        $("#data-wrapper2").append(response.mobile_html);
                    } else if (type === 'desktop' && response.html) {
                        $("#data-wrapper").append(response.html);
                    }

                    // Hide button if no more data
                    if ((type === 'mobile' && !response.mobile_html) ||
                        (type === 'desktop' && !response.html)) {
                        $('.load-more-data[data-type="' + type + '"]').hide();
                    }
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occurred');
                    $('.auto-load').hide();
                    $('.load-more-data').prop('disabled', false);
                });
        }
    </script>
@endsection
