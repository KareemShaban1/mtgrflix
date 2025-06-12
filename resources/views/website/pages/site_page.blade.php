@extends('website.layout.master')

@section('title')
	متجر فليكس
@endsection
@section('content')

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--start breadcrumb-->
        <section class="py-3 border-bottom border-top  d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item active" aria-current="page">  {{ $page->title ?? '' }}  </li>
                                <i class='bx bx-chevron-left'></i>
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"> {{ __('site.home') }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!--end breadcrumb-->

        <!--start page content-->
        <section class="py-5 py-lg-4 policy">
            <div class="container">
                <h6 class="text-primary pb-4">{{ $page->title ?? '' }}  </h6>


                {!! $page->content ?? '' !!}
              
            
            </div>
        </section>
    
        <!--end start page content-->
    </div>
</div>
<!--end page wrapper -->
@endsection