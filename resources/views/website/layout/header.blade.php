<div class="header-wrapper">
    @if ($advertisement->active === 1)
    <!-- top-menu -->
    <a href="{{ $advertisement->link }}">
        <div class="top-menu" id="topAdBar" style="background: {{ $advertisement->background }}">
            <div class="container-fluid d-flex align-items-center justify-content-center position-relative">
                <button class="ad-close-btn" id="closeAdBtn">×</button>
                <div class="marquee-wrapper">
                    <div class="marquee-text" dir="auto"> <!-- Add dir="auto" here -->

                        <span style="color: {{ $advertisement->color }}">
                            {{ $advertisement->text }}

                        </span>

                    </div>
                </div>
            </div>
        </div>
    </a>
    @endif
</div>


<!-- header-content -->
<div class="header-content bg-header">
    <div class="container">
        <div class="row align-items-center justify-content-between ">

            <!--  My Account + Cart + Search (Aligned to the far right on large screens and mobile) -->
            <div class="col-auto d-flex align-items-center gap-3 text-white my-account">



                <!-- Login Modal Start-->
                <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-header border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <i class="bx bx-user-circle text-primary" style="font-size: 80px;"></i>
                                <h5 class="mb-3 mt-2">{{ __('site.login') }} </h5>
                                <p class="text-dark"> {{ __('site.enter_login_to_proceed') }} </p>

                                
                                <div class="mb-3 d-flex">
                                    <input type="tel" class="form-control text-center flex-grow-1"
                                        id="phoneInput" placeholder="أدخل رقم هاتفك" required name="phone">
                                    <div class="dropdown me-2">
                                        <button class="btn btn-light dropdown-toggle d-flex align-items-center"
                                            type="button" id="countryDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="selectedFlag">{{ session('flag', '🇸🇦') }}</span>
                                            <span id="selectedCode">+{{ session('calling_code', '966') }}</span>
                                            <!-- <span id="selectedCurrency">{{ session('currency', 'SAR') }}</span> -->
                                        </button>
                                       
                                        <ul class="dropdown-menu country-list" aria-labelledby="countryDropdown"
                                            style="max-height: 200px; overflow-y: auto;">
                                            @php $activeSet = false; @endphp

@foreach ($countries as $country)
    @php
        $isActive = !$activeSet && session('country') == $country->code;
    @endphp

    <li>
        <a class="dropdown-item {{ $isActive ? 'active' : '' }}"
           href="#" data-code="+{{ $country->code }}"
           data-flag="{{ $country->flag }}">
            {!! $country->flag !!} {{ $country->name }} (+{{ $country->code }})
        </a>
    </li>

    @if ($isActive)
        @php $activeSet = true; @endphp
    @endif
@endforeach

                                        </ul>

                                    </div>
                                </div>
                                <button class="btn btn-primary w-100"
                                    id="loginButton">{{ __('site.continue') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Login Modal End -->

                <!-- Modal OTP Start -->
                <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-header border-0 position-relative">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <button type="button" class="btn position-absolute top-0 end-0 m-3 bg-light"
                                    id="backToLogin">
                                    <i class="bx bx-arrow-to-right" style="font-size: 24px;"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <i class="bx bx-lock text-primary" style="font-size: 80px;"></i>
                                <h5 class="mb-3 mt-2">{{ __('site.login') }}</h5>
                                <p class="mb-1 mt-2 text-dark">{{ __('site.enter_code_whatsapp') }}</p>
                                <i class="bx bxl-whatsapp text-success fa-3x"></i>
                                <p class="text-dark">
                                    {{ __('site.verification_required') }}<br>
                                    {{ __('site.sent_to') }}
                                </p>
                                <strong id="userPhone" class="text-dark"></strong>

                                <div class="d-flex justify-content-center otp-container pt-4">
                                    <input type="tel" inputmode="numeric" pattern="[0-9]*"
                                        class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
                                    <input type="tel" inputmode="numeric" pattern="[0-9]*"
                                        class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
                                    <input type="tel" inputmode="numeric" pattern="[0-9]*"
                                        class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
                                    <input type="tel" inputmode="numeric" pattern="[0-9]*"
                                        class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
                                </div>


                                <button class="btn btn-primary w-100 mt-3"
                                    id="verifyOtpButton">{{ __('site.verify') }}</button>
                                <button class="btn btn-secondary w-100 mt-2" id="sendOtpButton"
                                    disabled>{{ __('site.resend') }}</button>

                                <p class="mt-2 text-muted"><span id="resendTimer"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal OTP End -->

                <!-- User Info Modal Start -->
                <div class="modal fade" id="userInfoModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <h5><i class="fas fa-user-plus pb-4"></i> {{ __('site.login') }}</h5>

                                <div class="mb-3">
                                    <label for="firstName"
                                        class="form-label text-dark">{{ __('site.first_name') }}</label>
                                    <input type="text" class="form-control mt-1" id="firstName"
                                        placeholder="{{ __('site.first_name_placeholder') }}" required
                                        dir="rtl">
                                </div>

                                <div class="mb-3">
                                    <label for="lastName"
                                        class="form-label text-dark">{{ __('site.last_name') }}</label>
                                    <input type="text" class="form-control mt-1" id="lastName"
                                        placeholder="{{ __('site.last_name_placeholder') }}" required
                                        dir="rtl">
                                </div>

                                <div class="mb-3">
                                    <label for="email"
                                        class="form-label text-dark">{{ __('site.email') }}</label>
                                    <input type="email" class="form-control mt-1" id="email"
                                        placeholder="{{ __('site.email_placeholder') }}" required dir="rtl">
                                </div>

                                <button class="btn btn-primary w-100 mt-3"
                                    id="saveUserInfo">{{ __('site.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- User Info Modal End -->



                <div class="dropdown d-none" id="userDropdown">
                    <a class="btn dropdown-toggle d-flex align-items-center text-white" href="#"
                        role="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('frontend/assets/image/avatar_male.webp') }}" alt="User"
                            class="profile-img">
                        <div class="d-none d-xl-block fs-6">
                            <div>{{ __('site.welcome') }}</div>
                            <small id="userName">{{ Auth::check() ? Auth::user()->name : '' }}</small>
                        </div>
                    </a>

                    <ul class="dropdown-menu border-0 shadow-lg">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3"
                                href="{{ route('my-notification') }}">
                                <i class="bx bx-bell fs-5"></i> {{ __('site.notifications') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3"
                                href="{{ route('my-orders') }}">
                                <i class="bx bx-package fs-5"></i> {{ __('site.orders') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3"
                                href="{{ route('my-profile') }}">
                                <i class="bx bx-user fs-5"></i> {{ __('site.my_account') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger d-flex align-items-center gap-3"
                                href="{{ route('logout') }}" id="logoutButton">
                                <i class="bx bx-log-out fs-5"></i> {{ __('site.logout') }}
                            </a>
                        </li>
                    </ul>
                </div>




                <!-- cart shop -->
                <div class="d-flex align-items-center gap-3 cart-shop">
                    <button class="btn d-md-none search-mobile" id="search-toggle-mobile">
                        <i class='bx bx-search text-white'></i>
                    </button>
                    @php
                    use App\Models\Cart;

                    if (auth()->check()) {
                    $cart = Cart::where('user_id', auth()->id())
                    ->where('status', 'open')
                    ->with('items')
                    ->first();
                    } else {
                    $sessionToken = session('cart_session_token');
                    $cart = $sessionToken
                    ? Cart::where('session_token', $sessionToken)
                    ->where('status', 'open')
                    ->with('items')
                    ->first()
                    : null;
                    }

                    $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                    $cartTotal = $cart ? $cart->items->sum(fn($item) => $item->final_price * $item->quantity) : 0;
                    @endphp

                    <a href="{{ route('cart') }}"
                        class="d-flex align-items-center text-white text-decoration-none position-relative cart-mobile">
                        <i class="fa-solid fa-bag-shopping fs-3 m-2"></i>

                        @if ($cartCount > 0)
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                        @endif

                        <span class="d-none d-md-block fs-6">
                            <div>{{ __('site.cart') }}</div>
                            <small class="text-white">
                                {{ $cartTotal }}
                                @php
                                $currency = session('currency', 'SAR');
                                $symbol = session('symbol');
                                @endphp

                                @if ($currentCurrency === 'SAR')
                                <img src="{{ asset('frontend/assets/image/ryal-wh.png') }}"
                                    class="logo-product" alt="SAR">
                                @else
                                <span class="text-primary d-inline"
                                    id="currencySymbol">{{ $symbol }}</span>
                                @endif

                            </small>
                        </span>
                    </a>

                    <!-- After Login -->
                    <a href="#"
                        class="align-items-center text-white text-decoration-none account-mobile user_login {{ auth()->check() ? '' : 'd-none' }}  after_login"
                        id="profileIconMobile">
                        <img src="{{ auth()->user()->image_url ?? asset('frontend/assets/image/avatar_male.webp') }}"
                            alt="User" class="profile-img"
                            style="width: 40px; height: 40px; border-radius: 50%;">
                        <span id="welcomeMessage" class="d-none d-md-block fs-6">
                            <div>{{ __('site.welcome') }}</div>
                            <small id="userNameMobile">{{ optional(auth()->user())->first_name }}</small>
                        </span>
                    </a>

                    <!-- Before Login -->
                    <a href="#"
                        class="d-flex align-items-center text-white text-decoration-none account-mobile m-2 user_unlogin {{ auth()->check() ? 'd-none' : '' }} before_login"
                        data-bs-toggle="modal" data-bs-target="#loginModal" id="loginLink">
                        <i class='bx bx-user fs-3 m-2'></i>
                        <span class="d-none d-md-block fs-6">
                            <div>{{ __('site.my_account') }}</div>
                            <small class="text-white">{{ __('site.login') }}</small>
                        </span>
                    </a>



                </div>
            </div>

            <!-- Account Image Button (On Mobile After Login) -->
            <a href="#" id="profileIconDesktop"
                class="d-none align-items-center text-white text-decoration-none account-desktop">
                <img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User"
                    class="profile-img">
            </a>

            <!--  Account Image (Always Visible After Login) -->
            <a href="#" id="profileIconMobile"
                class="d-none align-items-center text-white text-decoration-none account-mobile">
                <img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User"
                    class="profile-img">
            </a>

            <!--  User modal on mobile (hidden by default) -->
            <div class="modal user-modal fade" id="mobileUserModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- The modal (upon login) -->
                        <div class="modal-header border-0 d-flex w-100">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="d-flex align-items-center ms-3">
                                <img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User"
                                    class="profile-img-modal">
                                <div class="welcome-text ms-2">
                                    <div>{{ __('site.welcome') }}</div>
                                    <small
                                        id="userNameMobile">{{ Auth::check() ? Auth::user()->name : __('name') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body text-center">
                            <hr class="my-3">
                            <ul class="list-unstyled text-start">
                                <li><a class="dropdown-item d-flex align-items-center gap-3"
                                        href="{{ route('my-notification') }}">
                                        <i class="bx bx-bell fs-5"></i> {{ __('site.notifications') }}
                                    </a></li>
                                <li><a class="dropdown-item d-flex align-items-center gap-3"
                                        href="{{ route('my-orders') }}">
                                        <i class="bx bx-package fs-5"></i> {{ __('site.orders') }}
                                    </a></li>

                                <li><a class="dropdown-item d-flex align-items-center gap-3"
                                        href="{{ route('my-profile') }}">
                                        <i class="bx bx-user fs-5"></i> {{ __('site.my_account') }}
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger d-flex align-items-center gap-3"
                                        href="{{ route('logout') }}" id="logoutButton">
                                        <i class="bx bx-log-out fs-5"></i> {{ __('site.logout') }}
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>



            <!--  Logo + Menu (Visible only on mobile, in correct RTL order) -->
            <div class="col-auto d-xl-none d-flex align-items-center gap-3">
                <!-- Menu -->
                <div class="mobile-toggle-menu order-1" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar">
                    <i class="bx bx-menu fs-2 text-white"></i>
                </div>
                <!-- Logo -->
                <a href="{{ route('home') }}" class="order-0">
                    <img src="{{ asset('frontend') }}/assets/image/logo.avif" class="logo-icon"
                        alt="Logo" />
                </a>
            </div>

            <!--  Logo in the center (Visible only on large screens) -->
            <div class="col text-center d-none d-xl-block">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('frontend') }}/assets/image/logo.avif" class="logo-icon"
                        alt="Logo" />
                </a>
            </div>

            <!--  Language + Currency + Search (Far left on large screens) -->
            <div class="col-auto d-none d-xl-flex align-items-center gap-3">

                <!-- Search (Appears on large screens) -->
                <button class="btn btn-dark" id="search-toggle"><i class='bx bx-search'></i></button>

                <!-- Language Selection -->
                <div class="dropdown">
                    <a class="btn btn-dark text-white dropdown-toggle d-flex align-items-center" href="#"
                        role="button" id="language-dropdown" data-bs-toggle="dropdown">
                        <i class="flag-icon flag-icon-{{ app()->getLocale() }} me-2" id="selected-flag"></i>
                        <span id="selected-language">{{ LaravelLocalization::getCurrentLocaleName() }}</span>
                    </a>
                    <ul class="dropdown-menu" id="language-menu">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);"
                                data-lang="{{ $localeCode }}"
                                data-url="{{ LaravelLocalization::getLocalizedURL($localeCode) }}">
                                <i class="flag-icon flag-icon-{{ $localeCode }} me-2"></i>
                                {{ $properties['native'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Currency Selection -->
                <div class="dropdown">
                    <a class="btn btn-dark text-white dropdown-toggle" href="#" role="button"
                        id="currency-dropdown" data-bs-toggle="dropdown">
                        {{ $currentCurrency }}
                    </a>
                    <ul class="dropdown-menu" id="currency-menu" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($currencies as $currency)
                        <li>
                            <a class="dropdown-item" href="#" data-currency="{{ $currency->code }}"
                            data-code="{{ $currency->code }}">
                                {{ $currency->name }}
                                ({{ $currency->code }})
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>



            </div>
        </div>
    </div>
</div>

<!-- search-box -->
<div id="search-box"
    class="d-none position-fixed w-100 h-100 d-flex align-items-start pt-5 justify-content-center"
    style="background: rgba(0, 0, 0, 0.5); top: 0; left: 0; z-index: 9999;">
    <div class="bg-white p-3 rounded shadow-lg" style="width: 90%; max-width: 400px; position: relative;">
        <div class="input-group ">
            <span class="input-group-text"><i class="bx bx-search" id="search-icon"></i></span>
            <input type="text" class="form-control text-end" id="search-input"
                placeholder="ادخل كلمة البحث هنا">
        </div>
        <div id="search-results" class="mt-2 text-center text-dark d-none">{{ __('site.no_search_results') }}
        </div>
    </div>
</div>

<!--primary-menu  -->
<div class="primary-menu ">
    <nav class="navbar navbar-expand-xl w-100 navbar-dark container mb-0 p-0">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" style="width: 85%;">
            <!-- offcanvas-header -->
            <div class="offcanvas-header">
                <button type="button" class="btn-close bg-danger " data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>

                <!-- currency-dropdown -->
                <div class="dropdown dropdown-currency">
                    <a class="btn btn-dark text-white dropdown-toggle" href="#" role="button"
                        id="currency-dropdown" data-bs-toggle="dropdown">{{ $currentCurrency }}</a>
                    <ul class="dropdown-menu" id="currency-menu" style="max-height: 300px; overflow-y: auto;">

                        @foreach ($currencies as $currency)
                        <li>
                            <a class="dropdown-item" href="#" data-currency="{{ $currency->code }}"
                            data-code="{{ $currency->code }}">
                                {{ $currency->name }}
                                ({{ $currency->code }})
                            </a>
                        </li>
                        @endforeach

                    </ul>
                </div>
                <!--  language-dropdown-->
                <div class="dropdown">
                    <a class="btn btn-dark text-white dropdown-toggle d-flex align-items-center" href="#"
                        role="button" id="language-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="flag-icon flag-icon-{{ app()->getLocale() }} me-2" id="selected-flag"></i>
                        <span id="selected-language">{{ LaravelLocalization::getCurrentLocaleName() }}</span>
                    </a>
                    <ul class="dropdown-menu" id="language-menu">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                <i class="flag-icon flag-icon-{{ $localeCode }} me-2"></i>
                                {{ $properties['native'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>




                <!-- <div class="offcanvas-logo"><img src="{{ asset('frontend') }}/assets/image/logo.avif" width="50" alt="" > </div> -->
            </div>

            <!--  offcanvas-body-->
            <div class="offcanvas-body primary-menu  ">
                <ul class="navbar-nav justify-content-center flex-grow-1 gap-1 my-2 ">

                    @foreach ($categories as $cat)
                    <li class="nav-item d-flex align-items-center flex-row-reverse ">
                        <img src="{{ $cat->image_url }}" alt="{{ $cat->name }} "
                            class="d-md-none rounded-circle me-2" width="30" height="30">
                        <a class="nav-link  "
                            href="{{ route('category', ['categorySlug' => $cat->slug, 'categoryId' => $cat->identifier]) }}">{{ $cat->name }}
                        </a>
                    </li>
                    @endforeach


                </ul>
            </div>
        </div>
    </nav>
</div>
</div>