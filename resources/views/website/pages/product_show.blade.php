<!doctype html>
<html lang="{{ app()->getLocale() }}">

@include('website.layout.head')


@section('meta')
    @if (isset($product->seo_description))
        <meta name="description" content="{{ $product->description_seo }}">
    @endif
@endsection

<body style="padding-bottom: 100px;">
    <!--wrapper-->
    <div class="wrapper">
        @include('website.layout.header')
        @php
            $locale = app()->getLocale();
        @endphp
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">

                <!--start product detail-->
                <section class="">
                    <form action="{{ route('checkout') }}" method="post" id="orderForm">
                        @csrf
                        @method('POST')
                        <input type="hidden" class="product_price" name="total"
                            value="{{ $product->getEffectivePrice() }}"
                            data-original-price="{{ $product->getEffectivePrice() }}">


                        <input type="hidden" class="product_id" name="product_id" value="{{ $product->id }}">


                        <div class="">
                            <div class="product-detail-card ">
                                <div class="product-detail-body">
                                    <div class="row g-0">
                                        <div class="col-12 col-lg-5">
                                            <img src="{{ asset('storage/' . $product->images) }}"
                                                class="img-fluid w-100 fixed-image " alt="product image">
                                        </div>

                                        <div class="col-12 col-lg-7">
                                            <div class="container">
                                                <div class="product-info-section p-1 py-5">
                                                    <h3 class="mt-1 mt-lg-0 mb-0 fw-bold"> {{ $product->name }}</h3>
                                                    <p class="mt-3 mt-lg-3 mb-0"> {{ $product->sub_title }} </p>
                                                    <div
                                                        class="product-rating d-flex align-items-center justify-content-between mt-2">
                                                        @include('website.partials.ratings', [
                                                            'rating' => $averageRating,
                                                            'reviewsCount' => $reviewsCount
                                                        ])


                                                        <div class="sharing">
                                                            <div class="share-container">
                                                                <button type="button" class="share-btn"
                                                                    id="shareButton">
                                                                    <i class="fas fa-share-alt"></i>
                                                                </button>
                                                                <div class="share-menu d-none" id="shareMenu">
                                                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}"
                                                                        class="share-icon fb" target="_blank"
                                                                        rel="noopener noreferrer">
                                                                        <i class="fab fa-facebook-f"></i>
                                                                    </a>
                                                                    <a href="https://wa.me/?text={{ urlencode(Request::url()) }}"
                                                                        class="share-icon wa" target="_blank"
                                                                        rel="noopener noreferrer">
                                                                        <i class="fab fa-whatsapp"></i>
                                                                    </a>
                                                                    <a href="https://x.com/intent/tweet?url={{ urlencode(Request::url()) }}"
                                                                        class="share-icon x" target="_blank"
                                                                        rel="noopener noreferrer">
                                                                        <i class="fab fa-x-twitter"></i>
                                                                    </a>
                                                                    <a href="mailto:?subject=Check this out&body={{ urlencode(Request::url()) }}"
                                                                        class="share-icon mail">
                                                                        <i class="fas fa-envelope"></i>
                                                                    </a>
                                                                    <a href="javascript:;" class="share-icon link"
                                                                        id="copyLink">
                                                                        <i class="fas fa-link"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="d-flex align-items-center mt-0 gap-2 product-price">
                                                        <div class="d-flex align-items-center mt-0 gap-2 product-price">
                                                            @include('website.partials.price', [
                                                                'class1' =>
                                                                    'h6  pt-1 text-secondary text-decoration-line-through small-price fs-3',
                                                                'class2' => 'h6 fw-bold text-danger fs-1',
                                                                'class3' => 'h6 fw-bold text-danger fs-1',
                                                            ])
                                                        </div>


                                                    </div>

                                                    <div class="mt-3">
                                                        {!! $product->{'description_' . app()->getLocale()} ?? '' !!}
                                                        <div class="seo pb-2">
                                                            @if (!empty($product->tags))
                                                                @foreach ($product->tags as $tag)
                                                                    <a href="{{ route('category.byTag', ['tag' => $tag, 'id' => $product->id]) }}"
                                                                        class="inline-flex items-center justify-center group text-gray-500 hover:text-gray-600 text-sm mb-2 rtl:ml-2 ltr:mr-2 transition duration-300">
                                                                        <span
                                                                            class="fix-align underline group-hover:no-underline">{{ $tag }}</span>
                                                                    </a>
                                                                @endforeach
                                                            @endif
                                                        </div>

                                                    </div>


                                                    <div
                                                        class="d-flex gap-2 py-3 d fixed-section bg-white justify-content-around">
                                                        <div class="img-right d-flex ">
                                                            <img src="{{ asset('storage/' . $product->category?->image ?? '') }}"
                                                                class="img-bar" alt="">
                                                            <div class="detales-bar me-3">
                                                                <h6 text> {{ $product->category?->name }} </h6>
                                                                <h6> {{ $product->name }} </h6>
                                                            </div>
                                                        </div>

                                                        <div class="buton-shop d-flex">
                                                            <a href="">
                                                                <div
                                                                    class="h6 fw-bold text-dark mt-3 m-3 price  new_price">
                                                                    {{ $product->getEffectivePrice() }}
                                                                    @include('website.partials.currency', [
                                                                        'currency' => $product->promotional_price,
                                                                    ])
                                                                </div>
                                                            </a>
                                                            <a href="" class="text-white  m-1" id="buy_now">
                                                                <div class="d-flex justify-content-center">
                                                                    <button class="animated-btn-price-shop"
                                                                        type="submit" name="action" value="buy_now">
                                                                        {{ __('site.buy_now') }}
                                                                    </button>
                                                                </div>
                                                            </a>
                                                            <a href="" class="text-white  m-1">
                                                                <div class="d-flex justify-content-center">
                                                                    <button class="animated-btn-price" type="submit"
                                                                        name="action" value="add_to_cart">
                                                                        {{ __('site.add_to_cart') }}
                                                                    </button>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="text-center pt-1 bg-white d-flex justify-content-center suport-qualty border border-2 mb-2">
                                                        <img src="{{ asset('frontend/assets/image/fair.png') }}" class="image-fair" alt="">

                                                        <p class="text-uppercase mb-1 fw-bold ms-1 pt-3 text-dark">
                                                            {{ __('site.purchased') }}
                                                        </p>

                                                        <p class="text-capitalize d-flex justify-content-center pt-3">
                                                            <span id="counter" class="text-dark" data-end-value="{{ $totalPurchases }}">
                                                                0
                                                            </span>
                                                        </p>

                                                        <p class="text-uppercase mb-1 fw-bold me-1 mt-3 text-dark">
                                                            {{ __('site.times') }}
                                                        </p>
                                                    </div>

                                                    @if ($product->productFields->count() > 0)
                                                        @foreach ($product->productFields as $field)
                                                            @if ($field->input_type === 'select')
                                                                <div
                                                                    class="d-flex justify-content-between flex-column flex-lg-row border py-3">
                                                                    <div class="mb-2 mb-lg-0 ">
                                                                        <h6 class="m-2">[{{ $field->name }}] *
                                                                        </h6>
                                                                    </div>
                                                                    <div class="options-container ms-2">



                                                                        <div
                                                                            class="d-flex justify-content-between flex-wrap">

                                                                            @foreach ($field->converted_options as $option)
                                                                                <label class="option ms-4 ">

                                                                                    <input type="checkbox"
                                                                                        class="option-checkbox"
                                                                                        name="product_options[{{ $product->id }}][select][]"
                                                                                        value='{{ $option['key'] }}'
                                                                                        data-extra-price="{{ $option['converted_price'] }}">
                                                                                    {{ $option['key'] }}
                                                                                    <span
                                                                                        class="m-2">({{ $option['converted_price'] }})

                                                                                        @include('website.partials.currency')

                                                                                    </span>
                                                                                </label>
                                                                            @endforeach

                                                                        </div>



                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($field->input_type === 'text')
                                                                <div class="border my-3 py-2">
                                                                    <div
                                                                        class="d-flex justify-content-between flex-column flex-lg-row   ">
                                                                        <div class="mb-2 mb-lg-0 m-2">
                                                                            <h6 class=""> [{{ $field->name }} ]
                                                                                *</h6>
                                                                            {{-- <p> [ {{ $field->description }} ]</p> --}}
                                                                        </div>
                                                                        <div class="options-container">
                                                                            <div class="mb-3">
                                                                                <textarea required name="product_options[{{ $product->id }}][text]" class="form-control" id="textArea"
                                                                                    rows="5" cols="55" placeholder=" {{ $field->name }}  "></textarea>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    {{-- //reviews --}}
                                                    @if ($product->reviews->count() > 0)
                                                        @include('website.partials.reviews', [
                                                            'product' => $product,
                                                        ])
                                                    @endif
                                                    {{-- end --}}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </section>
                <!--end product detail-->


                {{-- ///simi --}}

                @include('website.partials.similar')
            </div>
        </div>
        <!--end page wrapper -->

        <!--start about section-->
        @include('website.layout.about')
        <!--end about section-->

        @include('website.layout.footer')

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
    </div>
    <!--end wrapper-->
    @include('website.layout.js')
    <script src="{{ asset('frontend') }}/assets/js/product-details.js"></script>


    <script>
        var ENDPOINT = "{{ route('product.review', $product->id) }}";

        var page = 1;



        $(".load-more-data").click(function(event) {
            event.preventDefault();

            page++;

            infinteLoadMore(page);

        });



        /*------------------------------------------

        --------------------------------------------

        call infinteLoadMore()

        --------------------------------------------

        --------------------------------------------*/

        function infinteLoadMore(page) {

            $.ajax({

                    url: ENDPOINT + "?page=" + page,

                    datatype: "html",

                    type: "get",

                    beforeSend: function() {

                        $('.auto-load').show();

                    }

                })

                .done(function(response) {

                    if (response.html == '') {

                        $('.auto-load').html("لا توجد بيانات أخرى");

                        return;

                    }

                    $('.auto-load').hide();

                    $("#data-wrapper").append(response.html);

                })

                .fail(function(jqXHR, ajaxOptions, thrownError) {

                    console.log('Server error occured');

                });

        }


        function updateSort(value) {
            document.getElementById('sortInput').value = value;
        }
    </script>
    {{-- share --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shareButton = document.getElementById('shareButton');
            const shareMenu = document.getElementById('shareMenu');
            const copyLink = document.getElementById('copyLink');

            shareButton.addEventListener('click', function(e) {
                e.preventDefault();
                shareMenu.classList.toggle('d-none');
            });

            copyLink.addEventListener('click', function(e) {
                e.preventDefault();
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    toastr.success('Link copied to clipboard!');
                }).catch(err => {
                    toastr.error('Failed to copy link.');
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.option-checkbox');
            const priceInput = document.querySelector('.product_price');
            const priceDisplay = document.querySelector('.new_price');

            // Store the original base price as a float (from data attribute or fallback to value)
            const originalBasePrice = parseFloat(priceInput.dataset.originalPrice || priceInput.value);

            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', updateTotalPrice);
            });

            function updateTotalPrice() {
                let totalExtra = 0;

                document.querySelectorAll('.option-checkbox:checked').forEach(cb => {
                    totalExtra += parseFloat(cb.dataset.extraPrice);
                });

                const updatedTotal = (originalBasePrice + totalExtra).toFixed(2);

                priceInput.value = updatedTotal;

                if (priceDisplay) {
                    priceDisplay.innerHTML =
                        `${updatedTotal} `;
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderButton = document.getElementById('buy_now');
            let pendingOrderSubmit = false;

            if (orderButton) {
                orderButton.addEventListener('click', function(e) {
                    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

                    if (!isAuthenticated) {
                        e.preventDefault();
                        pendingOrderSubmit = true;
                        $('#loginModal').modal('show');
                    }
                });
            }

            // Listen for login success
            document.addEventListener('userLoggedIn', function() {
                if (pendingOrderSubmit) {
                    pendingOrderSubmit = false;
                    // Create a hidden input to set the action
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action';
                    hiddenInput.value = 'buy_now';
                    document.getElementById('orderForm').appendChild(hiddenInput);
                    document.getElementById('orderForm').submit();
                }
            });
        });
    </script>
    <script>
        document.getElementById('search-input').addEventListener('input', function() {
            let query = this.value.trim();

            // if (query.length < 2) {
            //     document.getElementById('search-results').classList.add('d-none');
            //     return;
            // }
            const url = @json(route('development.search'));
            fetch(`${url}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const resultsDiv = document.getElementById('search-results');
                    resultsDiv.innerHTML = '';

                    if (data.length === 0) {
                        resultsDiv.textContent = '{{ __('site.no_search_results') }}';
                    } else {
                        data.forEach(item => {
                            // console.log(item);
                            const a = document.createElement('a');
                            a.href = item.url;
                            a.className = 'd-block text-start mb-2';
                            a.textContent = item.name;
                            resultsDiv.appendChild(a);
                        });
                    }

                    resultsDiv.classList.remove('d-none');
                });
        });
    </script>
</body>

</html>
