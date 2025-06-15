<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8Q200X8TLP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-8Q200X8TLP');
    </script>

    @include('filament-seo::google-analytics')

    <meta name="google-site-verification" content="zzSNfelUfLN3UITTwMT4FJQCHDFpBDYFTW_LL3slYqo" />

    <!-- <meta name="google-site-verification" content="0v1g2q3r4s5t6u7v8w9x0y1z2a3b4c5d6e7f8g9h0i1j2k3l4m5n6o7p8q9r0s1t2u3v4w5x6y7z8a9b0c1d2e3f4g5h6i7j8k9l0m" /> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ========== ANTI-PREVIEW SYSTEM ========== -->
    @if(request()->is('my-orders/*'))
    <!-- Nuclear Option for Order Pages -->
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:title" content="Order">
    <meta property="og:description" content=" ">
    <meta property="og:image" content="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content=" ">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content=" ">
    <meta name="twitter:description" content=" ">
    <meta name="twitter:image" content="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔗</text></svg>">
    @else
    <!-- Normal SEO for other pages -->
    @include('filament-seo::google-tag')
    <x-filament-meta />
    <link rel="icon" href="{{ asset('frontend') }}/assets/image/logo.avif" type="image/png">
    @endif

    <!-- ========== COMMON ASSETS ========== -->
    <!--plugins-->
    <link href="{{ asset('frontend') }}/assets/plugins/OwlCarousel/css/owl.carousel.min.css" rel="stylesheet">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <!-- loader-->
    <link href="{{ asset('frontend') }}/assets/css/pace.min.css" rel="stylesheet">
    <script src="{{ asset('frontend') }}/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('frontend') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/assets/css/icons.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- <title>@yield('title')</title> -->
    @yield('css')

    <style>
        @media (max-width: 767px) {
            .payment-icon .col img {
                width: 55px !important;
                height: 35px !important;
                object-fit: contain;
            }


        }
        
        .slider-section .item img {
                width: 100%;
                height: auto;
                max-height: 550px;
                object-fit: contain;
            }
    </style>

    @if (app()->getLocale() == 'ar')
    <link href="{{ asset('frontend') }}/assets/css/app.css" rel="stylesheet">
    @else
    <link href="{{ asset('frontend') }}/assets/css/style.css" rel="stylesheet">
    @endif
</head>