<div class="reviwes">
    <div class="tab-pane fade show active" id="reviews">
        <div class="row">
            <div class="col col-lg-12">
                <div class="product-review">
                    <h6 class=" border text-center py-3 split-bg-warning">
                        تقييمات المنتج</h6>

                    <div class="review-list border mb-2">
                        <div class=" ">
                            <div id="data-wrapper">
                                @include('website.partials.product_reviews')



                            </div>
                        </div>
                        <hr />

                    </div>

                    <div class="pt-3">
                        <div class="d-flex justify-content-center">
                            <button class="animated-btn-price-more load-more-data">
                                {{ __('site.load_more') }}
                            </button>
                        </div>
                    </div>



                    <!-- Data Loader -->

                    <div class="auto-load text-center" style="display: none;">

                        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="60"
                            viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">

                            <path fill="#000"
                                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">

                                <animateTransform attributeName="transform" attributeType="XML" type="rotate"
                                    dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />

                            </path>

                        </svg>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
