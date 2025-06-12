@extends('website.layout.master')

@section('title')
    الرئيسية
@endsection
@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">





            <!--start shop cart-->
            <section class="py-4 notification">
                <div class="container">
                    <h3 class="d-none">{{ __('site.my_account') }}</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">


                                @include('website.partials.user-menu')


                                <div class="col-lg-8">
                                    <div class="card  mb-0">
                                        <h4 class="d-block  text-center py-3">{{ __('site.notifications') }}</h4>

                                        <div id="data-wrapper">

                                            @include('website.partials.notifications')

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

                                                    <animateTransform attributeName="transform" attributeType="XML"
                                                        type="rotate" dur="1s" from="0 50 50" to="360 50 50"
                                                        repeatCount="indefinite" />

                                                </path>

                                            </svg>

                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!--end row-->
                        </div>
                    </div>
                </div>
            </section>
            <!--end shop cart-->
        </div>
    </div>
    <!--end page wrapper -->
@endsection


@section('script')
    <script>
        var ENDPOINT = "{{ route('my-notification') }}";

        var page = 1;



        $(".load-more-data").click(function() {

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
    </script>
@endsection
