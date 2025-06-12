@extends('website.layout.master')

@section('title')
    الرئيسية
@endsection

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--start breadcrumb-->
            <section class="py-3 border-bottom border-top d-md-flex bg-light">
                <div class="container">
                    <div class="page-breadcrumb d-flex align-items-center">
                        <div class="ms-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item active" aria-current="page"> {{ __('site.cart') }} </li>
                                    <i class='bx bx-chevron-left'></i>
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}"> {{ __('site.home') }}</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <!--end breadcrumb-->
            @if ($cart && $cart->items->count() > 0)
                <!--start shop cart-->
                <section class="py-4 notification">
                    <form action="{{ route('submit.cart') }}" method="post" id="orderForm">
                        @method('POST')
                        @csrf
                        <div class="container">
                            <div class="shop-cart">
                                <div class="row">
                                    <div class="col-12 col-xl-8">
                                        <div class="shop-cart-list mb-3 p-3" id="cart-list">
                                            @php
                                                $total = 0;
                                            @endphp
                                            @if ($cart && $cart->items->count() > 0)
                                                @foreach ($cart->items ?? [] as $key => $item)
                                                    @php
                                                        $product = App\Models\Product::with([
                                                            'category',
                                                            'productCodes',
                                                            'productFields',
                                                        ])->find($item['product_id']);

                                                        if ($product) {
                                                            $decodedOptions = $item->options;

                                                            $textValue = '';
                                                            $selectedKeys = [];

                                                            if (isset($decodedOptions['text'])) {
                                                                $textValue = $decodedOptions['text'];
                                                            }

                                                            if (isset($decodedOptions['select'])) {
                                                                $selectedKeys = $decodedOptions['select'];
                                                            }
                                                            $price = $product->getEffectivePrice();
                                                            $promo = $product->promotional_price;
                                                            $rate = session('rate', 1);
                                                            $currency = session('currency', 'SAR');
                                                            $symbol = session('symbol');
                                                        }
                                                    @endphp

                                                    @if ($product)
                                                        <div class="row align-items-center g-3 row-cart">
                                                            <input type="hidden" class="product_price" name="product_price"
                                                                value="{{ $price ?? '' }}"
                                                                data-item-id="{{ $item['id'] }}">

                                                            <input type="hidden" class="product_quantity"
                                                                name="product_quantity" value="{{ $item['quantity'] }}">
                                                            <input type="hidden" name="cart" value="1">

                                                            <!-- top mobile-->
                                                            <div
                                                                class="col-12 d-flex align-items-center justify-content-between d-lg-none border-bottom pb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="cart-img">
                                                                        <img src="{{ asset('storage/' . $product->images ?? '') }}"
                                                                            width="90" alt="">
                                                                    </div>
                                                                    <div class="cart-detail m-4">
                                                                        <h6> {{ $product->name }}</h6>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-5">
                                                                    <a href="{{ route('remove.from.cart', $item['id']) }}"
                                                                        class="btn rounded-circle mb-5"
                                                                        style="background-color: rgb(241, 46, 46); display: flex; align-items: center; justify-content: center; width: 30px; height: 30px;">
                                                                        <i class='bx bx-x text-white ms-1'></i>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <!-- under mobile-->
                                                            <div
                                                                class="col-12 d-flex justify-content-between align-items-center d-lg-none mt-4">
                                                                <div class="d-flex align-items-center">
                                                                    <h6 class="text-primary me-2">
                                                                        {{ __('site.quantity') }}:</h6>
                                                                    <div class="input-group" style="width: 120px;">
                                                                        <button
                                                                            class="btn btn-outline-secondary decrement-btn p-0 d-flex align-items-center justify-content-center"
                                                                            type="button"
                                                                            data-item-id="{{ $item['id'] }}"
                                                                            style="width: 30px; height: 30px; margin-right:10px; margin-left:10px;">
                                                                            -
                                                                        </button>
                                                                        <input type="text"
                                                                            class="form-control text-center quantity-input border-start-0 border-end-0"
                                                                            value="{{ $item['quantity'] }}"
                                                                            data-item-id="{{ $item['id'] }}"
                                                                            data-product-id="{{ $product->id }}" readonly
                                                                            style="height: 30px; margin-left:10px">
                                                                        <button
                                                                            class="btn btn-outline-secondary increment-btn p-0 d-flex align-items-center justify-content-center"
                                                                            type="button"
                                                                            data-item-id="{{ $item['id'] }}"
                                                                            style="width: 30px; height: 30px;">
                                                                            +
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <h6 class="text-primary">{{ __('site.total') }}:
                                                                    <span class="item-total"
                                                                        data-item-id="{{ $item['id'] }}">
                                                                        {{ number_format($price * $item['quantity'], 2) }}
                                                                    </span>
                                                                    @include('website.partials.currency')
                                                                </h6>
                                                            </div>

                                                            <!-- larg screen-->
                                                            <div
                                                                class="col-12 col-lg-6 d-none d-lg-flex align-items-center gap-3">
                                                                <div class="cart-img text-center text-lg-start">
                                                                    <img src="{{ asset('storage/' . $product->images ?? '') }}"
                                                                        width="100" alt="">
                                                                </div>
                                                                <div class="cart-detail text-center text-lg-start">
                                                                    <h6>{{ $product->name }}</h6>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-lg-2 d-none d-lg-block">
                                                                <div class="cart-action text-center">
                                                                    <div class="input-group mx-auto" style="width: 120px;">
                                                                        <button
                                                                            class="btn btn-outline-secondary decrement-btn"
                                                                            type="button"
                                                                            data-item-id="{{ $item['id'] }}"
                                                                            style="margin-left:10px;">-</button>
                                                                        <input type="text"
                                                                            class="form-control text-center quantity-input"
                                                                            style="margin-left:10px;"
                                                                            value="{{ $item['quantity'] }}"
                                                                            data-item-id="{{ $item['id'] }}"
                                                                            data-product-id="{{ $product->id }}" readonly>
                                                                        <button
                                                                            class="btn btn-outline-secondary increment-btn"
                                                                            type="button"
                                                                            data-item-id="{{ $item['id'] }}">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-12 col-lg-4 d-none d-lg-flex justify-content-between">
                                                                <div class="cart-detail text-center text-lg-start">
                                                                    <h6 class="text-primary new_price d-inline">
                                                                        <span class="item-total"
                                                                            data-item-id="{{ $item['id'] }}">
                                                                            {{ number_format($price * $item['quantity'], 2) }}
                                                                        </span>
                                                                    </h6> @include('website.partials.currency')
                                                                </div>
                                                                <a href="{{ route('remove.from.cart', $item['id']) }}"
                                                                    class="btn rounded-circle"
                                                                    style="background-color: rgb(241, 46, 46); display: flex; align-items: center; justify-content: center; width: 30px; height: 30px;">
                                                                    <i class='bx bx-x text-white ms-1'></i>
                                                                </a>
                                                            </div>
                                                            @if (isset($product) && $product->type == 'service' && $product->productFields->isNotEmpty())
                                                                @foreach ($product->productFields ?? [] as $field)
                                                                    @if ($field->input_type === 'select')
                                                                        <div
                                                                            class="d-flex justify-content-between flex-column flex-lg-row border-top py-3">
                                                                            <div class="mb-2 mb-lg-0">
                                                                                <h6>{{ $field->name }}</h6>
                                                                            </div>
                                                                            <div class="options-container">
                                                                                @foreach ($field->converted_options ?? [] as $option)
                                                                                    <label class="option ms-4">
                                                                                        <input type="checkbox"
                                                                                            name="product_options[{{ $item['id'] }}][{{ $product->id }}][select][]"
                                                                                            value={{ $option['key'] }}
                                                                                            class="option-checkbox"
                                                                                            @if (in_array($option['key'], $selectedKeys)) checked @endif
                                                                                            data-product-id="{{ $product->id }}"
                                                                                            data-value="{{ $option['converted_price'] }}">
                                                                                        {{ $option['key'] }}
                                                                                        <span
                                                                                            class="m-2">({{ $option['converted_price'] }})</span>
                                                                                    </label>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if ($field->input_type === 'text')
                                                                        <div
                                                                            class="d-flex justify-content-between flex-column flex-lg-row border-top py-3">
                                                                            <div class="mb-2 mb-lg-0 ">
                                                                                <h6>
                                                                                    @if (app()->getLocale() == 'ar')
                                                                                        {{ $field->name }}<span>*</span>
                                                                                    @else
                                                                                        {{ $field->name }}*
                                                                                    @endif
                                                                                </h6>
                                                                            </div>
                                                                            <div class="options-container">
                                                                                <div class="mb-3">
                                                                                    <textarea required class="form-control" name="product_options[{{ $item['id'] }}][{{ $product->id }}][text]"
                                                                                        id="textArea" rows="5" cols="35" placeholder="{{ $field->name }}">{{ $textValue ?? '' }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <hr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-4 sticky-container">
                                        <div class="checkout-form p-3 bg-white border rounded shadow-sm">
                                            <div class="checkout-form p-3 bg-white border rounded shadow-sm">
                                                <div class="card border-0 bg-transparent shadow-none mb-3">
                                                    <div class="card-body">
                                                        <h6 class="fs-5 pt-2 mb-3">{{ __('site.order_summary') }}</h6>

                                                        <div
                                                            class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                            <p class="mb-0">{{ __('site.product_total') }}</p>
                                                            <h6 class="mb-0 order-product-count order-total-value-before">
                                                                {{ number_format($cart->getTotal(), 2) }}
                                                            </h6>
                                                            @include('website.partials.currency')
                                                        </div>

                                                        <!-- Coupon Code Section -->
                                                        <div class="mt-4">
                                                            <h6 class="mb-2">
                                                                <a class="text-decoration-none" data-bs-toggle="collapse"
                                                                    href="#couponCollapse" role="button"
                                                                    aria-expanded="false" aria-controls="couponCollapse"
                                                                    style="color: red">
                                                                    {{ __('site.have_discount_code') }}
                                                                </a>
                                                            </h6>

                                                            <div class="collapse" id="couponCollapse">
                                                                <div class="input-group">
                                                                    <input type="text" id="coupon_code"
                                                                        class="form-control rounded-0"
                                                                        placeholder="{{ __('site.enter_discount_code') }}"
                                                                        name="coupon_code"
                                                                        value="{{ $cart->coupon?->code }}">
                                                                    <button class="btn btn-primary rounded-0"
                                                                        id="applyDiscountBtn" type="button">
                                                                        <span
                                                                            id="applyBtnText">{{ __('site.apply_coupon') }}</span>
                                                                        <span id="applyBtnLoading"
                                                                            class="spinner-border spinner-border-sm d-none"></span>
                                                                    </button>
                                                                    <button class="btn btn-danger rounded-0 d-none"
                                                                        id="removeDiscountBtn" type="button">
                                                                        {{ __('filament.remove_coupon') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <small id="couponMessage" class="d-block mt-2"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="final-total" value="{{ $cart->getTotal() }}"
                                                name="total">
                                            <input type="hidden" id="discount-amount" value="0">

                                            <div class="card border-0 bg-transparent shadow-none mb-3">
                                                <div class="card-body">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center py-2 border-top">
                                                        <p class="mb-0">{{ __('site.total') }}</p>
                                                        <h6 class="mb-0 d-flex align-items-center gap-1">
                                                            <span class="order-total-value">
                                                                {{ number_format($cart->getTotal(), 2) }}</span>
                                                            @include('website.partials.currency')
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card border-0 bg-transparent shadow-none">
                                                <div class="card-body">
                                                    <div class="d-grid">
                                                        <button type="submit" id="buy_now"
                                                            class="btn btn-primary btn-ecomm w-100">{{ __('site.complete_order') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    </form>
                </section>
            @else
                <div class="flex items-center justify-center min-h-screen bg-gray-100">
                    <div class="text-center bg-white p-8 rounded-lg shadow-lg max-w-lg mx-auto ">
                        <h1 class="text-3xl font-bold text-gray-800 mb-6" style="padding-top: 100px">
                            {{ __('site.No_items_in_your_cart') }}
                        </h1>
                        <br>
                        <p class="text-lg text-gray-600 mb-6">
                            {{ __('site.cart_is_empty') }}
                        </p>
                        <br>
                        <a href="{{ route('home') }}" style="padding-bottom: 100px"
                            class="inline-block bg-grey-600 hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105">
                            {{ __('site.Go_to_Home') }}
                        </a>
                        <br>
                        <br>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--end page wrapper -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Order submission handling for guest users
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
                    location.reload();
                    document.getElementById('orderForm').submit();
                }
            });

            // Cart quantity and price calculations
            function updatePrices(itemId, newQuantity) {
                const basePrice = parseFloat($(`.product_price[data-item-id="${itemId}"]`).val());

                let optionsTotal = 0;
                $(`.option-checkbox[data-item-id="${itemId}"]:checked`).each(function() {
                    optionsTotal += parseFloat($(this).data('value'));
                });

                const itemTotal = (basePrice + optionsTotal) * newQuantity;

                $(`.item-total[data-item-id="${itemId}"]`).text(itemTotal.toFixed(2));
                updateOrderSummary();
            }

            function updateOrderSummary() {
                let subtotal = 0;

                $('.row-cart').each(function() {
                    const itemId = $(this).find('.quantity-input').data('item-id');
                    const quantity = parseInt($(`.quantity-input[data-item-id="${itemId}"]`).val());
                    const basePrice = parseFloat($(`.product_price[data-item-id="${itemId}"]`).val());

                    let optionsTotal = 0;
                    $(`.option-checkbox[data-item-id="${itemId}"]:checked`).each(function() {
                        optionsTotal += parseFloat($(this).data('value'));
                    });

                    subtotal += (basePrice + optionsTotal) * quantity;
                });

                const discountAmount = parseFloat($('#discount-amount').val()) || 0;
                const total = subtotal - discountAmount;

                $('.order-total-value-before').text(subtotal.toFixed(2));
                $('.order-total-value').text(total.toFixed(2));
                $('#final-total').val(total.toFixed(2));
            }

            function updateQuantityInDatabase(itemId, newQuantity) {
                return $.ajax({
                    url: "{{ route('cart.update-quantity') }}",
                    method: 'POST',
                    data: {
                        item_id: itemId,
                        quantity: newQuantity,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        console.error('Error updating quantity:', xhr.responseText);
                        alert('Error updating quantity. Please try again.');
                        return Promise.reject(xhr);
                    }
                });
            }

            $(document).on('click', '.increment-btn', function() {
                const itemId = $(this).data('item-id');
                const input = $(`.quantity-input[data-item-id="${itemId}"]`);
                let quantity = parseInt(input.val());
                const newQuantity = quantity + 1;

                input.val(newQuantity);
                updatePrices(itemId, newQuantity);

                updateQuantityInDatabase(itemId, newQuantity)
                    .then(response => {
                        if (!response.success) {
                            input.val(quantity);
                            updatePrices(itemId, quantity);
                        }
                    });
            });

            $(document).on('click', '.decrement-btn', function() {
                const itemId = $(this).data('item-id');
                const input = $(`.quantity-input[data-item-id="${itemId}"]`);
                let quantity = parseInt(input.val());

                if (quantity > 1) {
                    const newQuantity = quantity - 1;

                    input.val(newQuantity);
                    updatePrices(itemId, newQuantity);

                    updateQuantityInDatabase(itemId, newQuantity)
                        .then(response => {
                            if (!response.success) {
                                input.val(quantity);
                                updatePrices(itemId, quantity);
                            }
                        });
                }
            });

            $(document).on('change', '.option-checkbox', function() {
                const itemId = $(this).closest('.row-cart').find('.quantity-input').data('item-id');
                const quantity = parseInt($(`.quantity-input[data-item-id="${itemId}"]`).val());
                updatePrices(itemId, quantity);
            });
            const initialCoupon = $('#coupon_code').val().trim();

            if (initialCoupon) {
                setTimeout(() => {
                    $('#applyDiscountBtn').click();
                }, 100);
            }
            // Coupon Code Handling
            $('#applyDiscountBtn').on('click', function() {
                const couponCode = $('#coupon_code').val().trim();
                const msgDiv = $('#couponMessage');
                const totalBeforeDiscount = parseFloat($('.order-total-value-before').text() || 0);
                const applyButton = this;
                const removeButton = $('#removeDiscountBtn');
                const applyBtnText = $('#applyBtnText');
                const applyBtnLoading = $('#applyBtnLoading');

                msgDiv.removeClass('text-success text-danger');
                msgDiv.html('');

                if (!couponCode) {
                    msgDiv.addClass('text-danger');
                    msgDiv.text('{{ __('site.please_enter_coupon_code') }}');
                    return;
                }

                // Show loading state
                applyBtnText.addClass('d-none');
                applyBtnLoading.removeClass('d-none');
                $(applyButton).prop('disabled', true);

                $.ajax({
                    url: `/api/apply-coupon?code=${couponCode}&amount=${totalBeforeDiscount}`,
                    success: function(data) {
                        if (data.valid) {
                            msgDiv.removeClass('text-danger');
                            msgDiv.addClass('text-success');
                            msgDiv.text(
                                `${data.message || '{{ __('site.discount_applied') }}'}: ${data.discount.toFixed(2)}`
                            );

                            $('#discount-amount').val(data.discount);
                            $(applyButton).addClass('d-none');
                            $(removeButton).removeClass('d-none');
                            $('#coupon_code').prop('readonly', true);

                            updateOrderSummary();
                        } else {
                            msgDiv.removeClass('text-success');
                            msgDiv.addClass('text-danger');
                            msgDiv.text(data.message ||
                                '{{ __('site.invalid_coupon_code') }}');
                        }
                    },
                    error: function(error) {
                        msgDiv.removeClass('text-success');
                        msgDiv.addClass('text-danger');
                        msgDiv.text('{{ __('site.coupon_application_error') }}');
                        console.error('Error applying coupon:', error);
                    },
                    complete: function() {
                        applyBtnText.removeClass('d-none');
                        applyBtnLoading.addClass('d-none');
                        $(applyButton).prop('disabled', false);
                    }
                });
            });

            $('#removeDiscountBtn').on('click', function() {
                const msgDiv = $('#couponMessage');
                const applyButton = $('#applyDiscountBtn');
                const removeButton = this;

                $('#discount-amount').val(0);
                msgDiv.removeClass('text-success text-danger');
                msgDiv.html('');
                $(removeButton).addClass('d-none');
                $(applyButton).removeClass('d-none');
                $('#coupon_code').prop('readonly', false);
                $('#coupon_code').val('');

                updateOrderSummary();
            });

            @if (isset($code) && $code)
                $('#couponCollapse').addClass('show');
                $('#coupon_code').val('{{ $code }}');
                $('#applyDiscountBtn').addClass('d-none');
                $('#removeDiscountBtn').removeClass('d-none');
                $('#coupon_code').prop('readonly', true);
            @endif

            updateOrderSummary();
        });
    </script>
@endsection
