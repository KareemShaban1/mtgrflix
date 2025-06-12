<footer class="foter-ar  bg-footer">
    <img src="{{ asset('frontend') }}/assets/image/line.png" class="w-100 bg-dark" alt="">

    <!-- Top Footer -->
    <section class="py-5  ">
        <div class="container text-white">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 ">
                <div class="col">
                    <div class="footer-section3">
                        <h5 class="mb-2  fw-bold text-white"> {{ __('site.contactOwner') }}  </h5>
                        <div class="tags-box d-flex flex-wrap gap-1 ">
                            <div class="icons-about d-flex justify-content-end align-items-center">
                                <div class="m-2">
                                    <a href="{{ setting("tiktok") }}"><i
                                            class="fab fa-whatsapp fa-2x "></i> </a>
                                </div>
                                <div class="pt-3 ">
                                    <p class="text-white"><a href="https://wa.me/{{ setting("site_phone") }}"
                                            class="text-white">{{ setting("site_phone") }}</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="tags-box d-flex flex-wrap gap-1 mt-3">

                            <div class="icons-about d-flex justify-content-center align-items-center">
                                <div class=" m-2">
                                    <a href="{{ setting("site_email") }}"><i
                                            class="fas fa-envelope fa-2x"></i> </a>
                                </div>
                                <div class="pt-3">
                                    <p class="text-white"><a href="mailto:{{ setting("site_email") }}"
                                            class="text-white">{{ setting("site_email") }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="footer-section2">
                        <h5 class="mb-4  fw-bold text-white">{{ __('site.important_links') }}</h5>
                        <p><a href="{{ route('page','replacement-policy') }}"class="text-white"><i
                                    class='bx bx-chevrons-left text-primary'></i>{{ __('site.replacement_policy') }}</a></p>
                        <p><a href="{{ route('page','privacy-policy') }}"class="text-white"><i
                                    class='bx bx-chevrons-left  text-primary'></i>{{ __('site.privacy_policy') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootom Footer -->
    <section class="footer-strip  bg-footer text-center py-3   top-footer positon-absolute bottom-0">
        <div class="container">
            <div class="d-flex flex-row align-items-center justify-content-center gap-2 mb-4">
                <a href="https://e.business.sa/new-launch-msg">
                    <img src="{{ asset('frontend') }}/assets/image/busnines.png" class=" rounded"
                        alt="{{ __('site.trusted_platform') }}" style="width: 40px;">
                </a>
                <hp class="text-white mt-">{{ __('site.trusted_platform') }}</hp>
            </div>

            <div class="d-flex flex-column flex-lg-row align-items-center gap-3 justify-content-between">
                <p class="mb-0">{{ __('site.all_rights_reserved') }} | {{ date('Y') }} {{ __('site.store_name') }}.</p>
                <div class="payment-icon">
                    <div class="row row-cols-auto g-2 justify-content-end">
                        <div class="col">
                            <img src="{{ asset('frontend') }}/assets/image/New folder/visa.png"
                                class="img-fluid" alt="" />
                        </div>
                        <div class="col">
                            <img src="{{ asset('frontend') }}/assets/image/New folder/google.png"
                                class="img-fluid" alt="" />
                        </div>
                        <div class="col">
                            <img src="{{ asset('frontend') }}/assets/image/New folder/apple.png"
                                class="img-fluid" alt="" />
                        </div>
                        <div class="col">
                            <img src="{{ asset('frontend') }}/assets/image/New folder/mada.png"
                                class="img-fluid" alt="" />
                        </div>
                        <div class="col">
                            <img src="{{ asset('frontend') }}/assets/image/New folder/Stc_pay.png"
                                class="img-fluid" alt=""  />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</footer>
