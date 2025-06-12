<section class="p-4 bg-footer about">
    <div class="container m-auto text-center">
        <div class=" text-white">
            <img src="{{ asset('frontend') }}/assets/image/logo.avif" class="logo-icon mb-3" alt="Logo" />
            <h2 class=" text-white pb-3">{{ __('site.about') }} </h2>
            <p class="text-right">{{ setting('site_description_' . app()->getLocale()) }}</p>
            <h2 class=" text-white mt-4"> {{ __('site.follow') }} </h2>
            <div class="icons-about d-flex justify-content-center align-items-center">

                <div class="icon-tiktok m-3">
                    <a href="https://www.tiktok.com/@mtgrflix"><i class="fab fa-tiktok"></i> </a>
                </div>
                <div class="icon-tiktok">
                    <a href=" https://www.instagram.com/mtgrflixx/#">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                @php
                    $socials = setting('site_social') ?? [];
                    // dd($socials);
                @endphp
                {{-- @if (empty($socials))

                    <div class="icon-tiktok m-3">
                        <a href="https://www.tiktok.com/@mtgrflix"><i class="fab fa-tiktok"></i> </a>
                    </div>
                    <div class="icon-tiktok">
                        <a href=" https://www.instagram.com/mtgrflixx/#">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                @else
                    @foreach ($socials as $social)
                        @if (!empty($social['link']) && !empty($social['vendor']))
                            <div class="icon-tiktok m-3">
                                <a href="{{ $social['link'] }}" target="_blank">
                                    <i class="fab fa-{{ strtolower($social['vendor']) }}"></i>
                                </a>
                            </div>
                        @endif
                    @endforeach

                @endif --}}
            </div>
        </div>
    </div>
</section>
