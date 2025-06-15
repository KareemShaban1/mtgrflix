@extends('website.layout.master')

@section('title')
    الرئيسية
@endsection

@section('content')
    <div class="container d-flex justify-content-center ">


        <div class="col-12 " style="max-width: 900px" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

            @csrf
            {{-- Order Summary --}}
            <div class="card border-0 bg-transparent shadow-none mb-3">
                <div class="card-body">
                    <h6 class="fs-5 pt-2 mb-3">{{ __('site.order_summary') }}</h6>

                    {{-- <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <p class="mb-0">{{ __('site.total_products') }}</p>
                        <h6 class="mb-0 order-product-count">3</h6>
                    </div> --}}




                    @if (isset($cart))
                        <input type="hidden" name="cart" value="{{ $cart }}">
                    @endif

                    @if (isset($productId))
                        <input type="hidden" name="product_id" value="{{ $productId }}">
                        <input id="order_total" type="hidden" name="order_total" value="{{ $total }}">


                        @if (!empty($options[$productId]['text']))
                            <input type="hidden" name="product_options[{{ $productId }}][text]"
                                value="{{ $options[$productId]['text'] }}">
                        @endif

                        @if (!empty($options[$productId]['select']) && is_array($options[$productId]['select']))
                            @foreach ($options[$productId]['select'] as $checkbox)
                                @if (!empty($checkbox))
                                    <input type="hidden" name="product_options[{{ $productId }}][select][]"
                                        value="{{ $checkbox }}">
                                @endif
                            @endforeach
                        @endif

                    @endif


                    {{-- Coupon Code --}}
                    <div class="mt-4">
                        <h6 class="mb-2">
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#couponCollapse" role="button"
                                aria-expanded="{{ isset($code) && $code ? 'true' : 'false' }}"
                                aria-controls="couponCollapse" style="color: red">
                                {{ __('site.have_discount_code') }}
                            </a>
                        </h6>

                        <div class="collapse {{ isset($code) && $code ? 'show' : '' }}" id="couponCollapse">
                            <div class="input-group">
                                <input type="text" id="coupon_code" class="form-control rounded-start"
                                    placeholder="{{ __('site.enter_discount_code') }}" name="coupon_code"
                                    value="{{ $code ?? '' }}">
                                <button class="btn btn-primary rounded-end" id="applyDiscountBtn" type="button">
                                    <span id="applyBtnText">{{ __('site.apply_coupon') }}</span>
                                    <span id="applyBtnLoading" class="spinner-border spinner-border-sm d-none"></span>
                                </button>

                                <button class="btn btn-danger rounded-0 d-none" id="removeDiscountBtn" type="button">
                                    {{ __('filament.remove_coupon') }}
                                </button>
                            </div>
                        </div>

                        {{-- Success & Error Messages --}}
                        <small class="d-block mt-2" id="couponMessage"></small>
                    </div>
                </div>
            </div>

            {{-- Total Price --}}
            <div class="card border-0 bg-transparent shadow-none mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <p class="mb-0 fs-3">{{ __('site.total') }}</p>
                        <h6 class="mb-0 d-flex align-items-center gap-1 text-success fw-bold fs-3">
                            {{-- <span class="order-total-value">{{ $productPrice  ?? ''}}</span> --}}
                            <span class="order-total-value">{{ $total * session()->get('rate') }}</span>
                            @include('website.partials.currency')

                        </h6>
                    </div>
                </div>
            </div>


            <div id="unified-session" class="d-flex flex-column align-items-center justify-content-center mt-3"
                style=" margin: 10px auto;">

            </div>
            {{-- @if (app()->getLocale() === 'ar')
                <img src="{{ asset('frontend') }}/assets/image/trusted-pay/ar.png" alt="" width="80"
                    height="80">
            @else
                <img src="{{ asset('frontend') }}/assets/image/trusted-pay/en.png" alt="" width="80"
                    height="80">
            @endif --}}
        </div>
    </div>
@endsection

@section('script')
    @if (env('APP_ENV') === 'local' || env('APP_ENV') === 'testing')
        <script src="https://demo.myfatoorah.com/payment/v1/session.js"></script> {{-- Sandbox --}}
    @else
        <script src="https://sa.myfatoorah.com/payment/v1/session.js"></script> {{-- Live (SAU) --}}
    @endif

    <script>
        const totalEl = document.querySelector('.order-total-value');

        var sessionId = "{{ $sessionId }}";
        var countryCode = "{{ $countryCode }}";
        var currencyCode = "{{ session('currency', 1) }}";
        var amount = "{{ $total }}";
        var language = "{{ app()->getLocale() }}";
        console.log(amount);
        var config = {
            sessionId: sessionId,
            countryCode: countryCode,
            currencyCode: currencyCode,
            amount: amount,
            callback: payment,
            containerId: "unified-session",
            paymentOptions: ["ApplePay", "GooglePay", "STCPay", "Card"], //"GooglePay", "ApplePay", "Card", "STCPay"
            supportedNetworks: ["visa", "masterCard", "mada", "amex"], //"visa", "masterCard", "mada", "amex"
            language: language

        };


        myfatoorah.init(config);
        //myfatoorah.submitStcOtp("1234");

        //Embedded Functions
        function payment(response) {
            //Pass session id to your backend here

            //save order here to your backend  with all inpits values
            if (response.isSuccess) {
                submitOrderToBackend(response.sessionId, response.paymentType);

                switch (response.paymentType) {
                    case "ApplePay":
                        console.log("Apple Pay Response>>\n" + JSON.stringify(response));
                        break;
                    case "GooglePay":
                        console.log("Google Pay response >>\n" + JSON.stringify(response));
                        break;
                    case "Card":
                        console.log("Card Response >>\n" + JSON.stringify(response));
                        break;
                    case "StcPay":
                        console.log("STC Pay response >>\n" + JSON.stringify(response));
                        break;
                    default:
                        console.log("Unknown payment type >>\n" + JSON.stringify(response));
                        break;
                }
            } else {
                console.log("error", response);
            }
        }

        function updateAmount(amount) {
            myfatoorah.updateAmount(amount);
        }


        function submitOrderToBackend(sessionId, paymentType) {

            const formData = new FormData();
            const url = '{{ route('final-checkout') }}';

            formData.append('session_id', sessionId);

            const cartInput = document.querySelector('input[name="cart"]');
            if (cartInput) formData.append('cart', cartInput.value);

            formData.append('payment_type', paymentType);

            const amountElement = document.querySelector('.order-total-value');
            if (amountElement) formData.append('amount', amountElement.textContent.trim());

            const couponCode = document.getElementById('coupon_code');
            if (couponCode) formData.append('code', couponCode.value.trim());

            const productIdInput = document.querySelector('input[name="product_id"]');
            if (productIdInput) formData.append('product_id', productIdInput.value);

            document.querySelectorAll('input[name^="product_options"]').forEach(input => {
                if (input.name && input.value !== null) {
                    formData.append(input.name, input.value);
                }
            });


            // 🔐 Send to backend (adjust your route)
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {
                        window.location.href = data.url;
                    } else {
                        alert('خطأ أثناء الحفظ: ' + (data.message || ''));
                    }
                })
                .catch(err => console.error('Error submitting order:', err));
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const couponCodeInput = document.getElementById('coupon_code');
            const msgDiv = document.getElementById('couponMessage');
            const totalEl = document.querySelector('.order-total-value');
            const loading = document.getElementById('applyBtnLoading');
            const btnText = document.getElementById('applyBtnText');
            const applyButton = document.getElementById('applyDiscountBtn');
            const unifiedSession = document.getElementById("unified-session");
            const removeButton = document.getElementById('removeDiscountBtn');

            if (!couponCodeInput || !msgDiv || !totalEl || !loading || !btnText || !applyButton || !
                unifiedSession || !removeButton) {
                console.error('One or more required elements are missing from the DOM');
                return;
            }

            const originalOrderAmount = {{ $total }};
            const rate = {{ session('rate', 1) }};
            const initialCoupon = couponCodeInput.value.trim();

            if (initialCoupon) {
                setTimeout(() => {
                    applyButton.click();
                }, 100);
            }

            applyButton.addEventListener('click', async function() {
                const couponCode = couponCodeInput.value.trim();
                const currentAppliedCode = couponCodeInput.dataset.appliedCode;

                msgDiv.className = '';
                msgDiv.innerText = '';
                applyButton.classList.remove('btn-success', 'btn-danger');

                if (!couponCode) {
                    showError('من فضلك أدخل كود الخصم');
                    return;
                }

                if (currentAppliedCode === couponCode) {
                    showWarning('تم تطبيق هذا الكود مسبقاً');
                    return;
                }

                toggleLoading(true);

                try {
                    const response = await fetch(
                        `/api/apply-coupon?code=${encodeURIComponent(couponCode)}&amount=${originalOrderAmount}`
                    );

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.valid) {
                        handleSuccess(couponCode, data.discount, data.message);
                    } else {
                        handleInvalidCode(data.message);
                    }
                } catch (error) {
                    handleFetchError(error);
                } finally {
                    toggleLoading(false);
                }
            });

            couponCodeInput.addEventListener('input', function() {
                if (this.value !== this.dataset.appliedCode) {
                    this.dataset.appliedCode = '';
                    applyButton.classList.remove('btn-success');
                    msgDiv.className = '';
                    msgDiv.innerText = '';
                }
            });

            removeButton.addEventListener('click', function() {
                // Reset everything
                couponCodeInput.value = '';
                couponCodeInput.dataset.appliedCode = '';
                couponCodeInput.removeAttribute('readonly');

                msgDiv.className = '';
                msgDiv.innerText = '';
                applyButton.classList.remove('d-none');
                removeButton.classList.add('d-none');
                resetTotal();
            });

            // Helper Functions

            function toggleLoading(isLoading) {
                loading.classList.toggle('d-none', !isLoading);
                btnText.classList.toggle('d-none', isLoading);
                applyButton.disabled = isLoading;
            }

            function showError(message) {
                msgDiv.classList.add('text-danger');
                msgDiv.innerText = message;
                applyButton.classList.add('btn-danger');
            }

            function showWarning(message) {
                msgDiv.classList.add('text-warning');
                msgDiv.innerText = message;
            }

            function handleSuccess(code, discount, successMsg) {
                msgDiv.classList.remove('text-danger');
                msgDiv.classList.add('text-success');
                msgDiv.innerText = `${successMsg || 'تم تطبيق الخصم'}: ${discount.toFixed(2)}`;

                $('#discount-amount').val(discount);
                $(applyButton).addClass('d-none');
                $(removeButton).removeClass('d-none');
                $('#coupon_code').prop('readonly', true);

                couponCodeInput.dataset.appliedCode = code;

                const finalAmount = originalOrderAmount - discount;
                const convertedAmount = finalAmount * rate;

                totalEl.textContent = convertedAmount.toFixed(2);

                // ✅ Update MyFatoorah config to use discounted amount
                config.amount = finalAmount.toFixed(2);

                // ✅ Re-initialize MyFatoorah session
                unifiedSession.innerHTML = "";
                if (typeof myfatoorah !== 'undefined' && typeof myfatoorah.init === 'function') {
                    myfatoorah.init(config);
                }
            }


            function handleInvalidCode(message) {
                couponCodeInput.dataset.appliedCode = '';
                showError(message || 'كود غير صالح!');
                resetTotal();
            }

            function handleFetchError(error) {
                console.error('Coupon fetch error:', error);
                showError('حدث خطأ أثناء الاتصال بالخادم');
                resetTotal();
            }

            function resetTotal() {
                const convertedOriginalAmount = originalOrderAmount * rate;
                totalEl.textContent = convertedOriginalAmount.toFixed(2);

                config.amount = originalOrderAmount.toFixed(2);

                unifiedSession.innerHTML = "";
                if (typeof myfatoorah !== 'undefined' && typeof myfatoorah.init === 'function') {
                    myfatoorah.init(config);
                }

                $('#discount-amount').val('');
            }
        });
    </script>
@endsection
