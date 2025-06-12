<!doctype html>
<html lang="{{ app()->getLocale() }}">

@include('website.layout.head')

<body>
    <!--wrapper-->
    <div class="wrapper">

        <!--start top header wrapper-->
        @include('website.layout.header')
        <!--end top header wrapper-->

        @yield('content')

        <!--start about section-->
        @include('website.layout.about')
        <!--end about section-->

        <!--start footer section-->
        @include('website.layout.footer')
        <!--end footer section-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
    </div>
    <!--end wrapper-->

  @include('website.layout.js')
</body>

</html>
