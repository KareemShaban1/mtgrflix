@extends('website.layout.master')

@section('title')
    {{ __('site.home') }}
@endsection

@section('content')
@php
    $user= auth()->user();
@endphp
<div class="page-wrapper">
    <div class="page-content">
        <section class="py-3 border-bottom border-top d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="#">{{ __('site.my_account') }}</a></li>
                                <i class='bx bx-chevron-left'></i>
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('site.home') }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 notification">
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card shadow-none mb-3 mb-lg-0 border d-none d-lg-block">
                                    <div class="card-body">
                                        <div class="col-12 text-center">
                                            <label for="profileImageInput" class="d-inline-block position-relative" style="cursor: pointer;">
                                                <img id="profileImage" src="{{ $user->image_url }}" class="image-acount border" >
                                        
                                                <span id="removeImage" class="position-absolute start-50 translate-middle-x bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                                                      style="width: 20px; height: 20px; bottom: -6px; display: none; cursor: pointer;">
                                                    ✖
                                                </span>
                                            </label>
                                        </div>
                                        
                                    
                                        <div class="list-group list-group-flush">
                                            <a href="{{ route('my-notification') }}"
                                               class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('my-notification') ? 'active' : 'bg-transparent' }}">
                                                {{ __('site.notifications') }}
                                                <i class='bx bx-bell fs-5'></i>
                                            </a>
                            
                                            <a href="{{ route('my-orders') }}"
                                               class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('my-orders') ? 'active' : 'bg-transparent' }}">
                                                {{ __('site.orders') }}
                                                <i class='bx bx-cart fs-5'></i>
                                            </a>
                            
                                            <a href="{{ route('my-profile') }}"
                                               class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('my-profile') ? 'active' : 'bg-transparent' }}">
                                                {{ __('site.my_account') }}
                                                <i class='bx bx-user fs-5'></i>
                                            </a>
                            
                                            <a href="{{ route('logout') }}"
                                               class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-danger">
                                                {{ __('site.logout') }}
                                                <i class='bx bx-log-out fs-5'></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-lg-8">
                                <div class="card shadow-none mb-0">
                                    <div class="col-12 text-center d-block d-sm-none">
                                        <label for="profileImageInput" class="d-inline-block position-relative" style="cursor: pointer;">
                                            <img id="profileImage" src=" {{ $user->image_url }} " class="image-acount border" alt="Profile Image">
                                            
                                            <span id="removeImage" class="position-absolute start-50 translate-middle-x bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                                                  style="width: 20px; height: 20px; bottom: -6px; display: none; cursor: pointer;">
                                                ✖
                                            </span>
                                        </label>
                                        {{-- <input type="file" id="profileImageInput" class="d-none" accept="image/*"> --}}
                                    </div>
                                    <h4 class="text-center py-2">{{ __('site.my_account') }}</h4>

                                    <div class="card-body border mb-2">
                                        <form class="row g-3" method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="file" id="profileImageInput" class="d-none" accept="image/*" name="image">

                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('site.first_name') }}</label>
                                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('site.last_name') }}</label>
                                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('site.birthday') }}</label>
                                                <input type="date" name="birthday" class="form-control" value="{{ old('birthday', $user->birthday) }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('site.gender') }}</label>
                                                <select name="gender" class="form-control">
                                                    <option value="">{{ __('site.select_gender') }}</option>
                                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('site.male') }}</option>
                                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('site.female') }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('site.email') }}</label>
                                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('site.phone') }}</label>
                                                <div class="input-group">
                                                    <div class="dropdown me-2">
                                                        <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="countryDropdown" data-bs-toggle="dropdown">
                                                            <span id="selectedFlag">{!! $user->country_flag ?? '🇸🇦' !!}</span>
                                                            <span id="selectedCode">{{ $user->country_code ?? '966' }}</span>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="countryDropdown" id="country-list" style="max-height: 200px; overflow-y: auto;">
                                                            @foreach($countries as $country)
                                                                <li>
                                                                    <a class="dropdown-item country-item" href="#"
                                                                        data-code="{{ $country->code }}"
                                                                        data-flag="{{ $country->flag }}">
                                                                        {!! $country->flag !!} {{ $country->name }} (+{{ $country->code }})
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <input type="hidden" name="country_code" id="country_code_input" value="{{ $user->country_code }}">
                                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-ecomm">{{ __('site.save') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const items = document.querySelectorAll('.country-item');
        const selectedFlag = document.getElementById('selectedFlag');
        const selectedCode = document.getElementById('selectedCode');
        const hiddenInput = document.getElementById('country_code_input');

        items.forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const flag = this.getAttribute('data-flag');
                const code = this.getAttribute('data-code');
                selectedFlag.innerHTML = flag;
                selectedCode.innerHTML = `+${code}`;
                hiddenInput.value = code;
            });
        });
    });
</script>
@endsection
