@extends('website.layout.master')

@section('title')
    {{ __('site.login') }}
@endsection

@section('css')
    <style>
        .login-card, .otp-modal {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.8s ease;
        }

        .login-card .form-control,
        .login-card .btn,
        .login-card .dropdown-toggle,
        .otp-input {
            border-radius: 10px;
        }

        .country-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .otp-container {
            margin: 20px 0;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 20px;
            margin: 0 5px;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        #otpModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
        }
    </style>
@endsection

@section('content')
    <!-- Login Form -->
    <div class="login-card m-auto my-5" id="loginFormContainer">
        <i class="bx bx-user-circle text-primary" style="font-size: 80px;"></i>
        <h5 class="mb-3 mt-2">{{ __('site.login') }}</h5>
        <p class="text-dark mb-4">{{ __('site.enter_phone') }}</p>

        <form id="phoneForm">
            @csrf
            <div class="mb-3 d-flex">
                <input type="tel" name="phone_number" class="form-control text-center flex-grow-1" id="phoneInput"
                    placeholder="{{ __('site.phone_placeholder') }}" required>

                <div class="dropdown me-2">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button"
                        id="countryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedFlag">🇪🇬</span>
                        <span id="selectedCode">+20</span>
                    </button>
                    <input type="hidden" name="country_code" id="countryCodeInput" value="+20">

                    <ul class="dropdown-menu country-list" aria-labelledby="countryDropdown">
                        @foreach ($countries as $country)
                            <li>
                                <a class="dropdown-item country-item" href="#" data-code="{{ $country->code }}"
                                    data-flag="{{ $country->flag }}">
                                    {!! $country->flag !!} {{ $country->name }} (+{{ $country->code }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" id="sendOtpButton">
                {{ __('site.continue') }}
            </button>
        </form>
    </div>

    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center otp-modal">
                <div class="modal-header border-0 position-relative">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" action="{{ route('verify.otp.blade') }}" id="otpForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="phone" id="hiddenPhoneNumber" value="{{ $phone_number ?? '' }}">
                    
                    <div class="modal-body">
                        <i class="bx bx-lock text-primary" style="font-size: 80px;"></i>
                        <h5 class="mb-3 mt-2">{{ __('auth.otp_verification') }}</h5>
                        <p class="mb-1 mt-2 text-dark">{{ __('auth.enter_otp_sent') }}</p>
                        <i class="bx bxl-whatsapp text-success fa-3x"></i>
                        <p class="text-dark">{{ __('auth.otp_required') }}<br>{{ __('auth.otp_sent_to') }}</p>
                        <strong id="userPhone" class="text-dark">{{ $phone_number ?? '' }}</strong>
    
                        <div class="d-flex justify-content-center otp-container pt-4">
                            <input type="hidden" name="otp" id="fullOtpInput">
                            @for($i = 0; $i < 4; $i++)
                                <input type="text" 
                                       id="otp-{{ $i }}"
                                       class="otp-input form-control text-center mx-1 rounded-3" 
                                       maxlength="1" 
                                       inputmode="numeric" 
                                       pattern="[0-9]*"
                                       oninput="handleOtpInput(this, {{ $i }})"
                                       onkeydown="handleOtpKeyDown(event, {{ $i }})"
                                       onpaste="handleOtpPaste(event)">
                            @endfor
                        </div>
    
                        <button type="submit" class="btn btn-primary w-100 mt-3" id="verifyOtpButton2">
                            {{ __('auth.verify') }}
                        </button>
                        <button type="button" class="btn btn-secondary w-100 mt-2" id="resendOtpButton">
                            {{ __('auth.resend_code') }}
                        </button>
    
                        <p class="mt-2 text-muted">
                            <span id="resendTimer">{{ __('auth.resend_in') }} <span id="countdown">60</span> {{ __('auth.seconds') }}</span>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize country dropdown
        document.querySelectorAll('.country-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const code = this.getAttribute('data-code');
                const flag = this.getAttribute('data-flag');

                document.getElementById('selectedFlag').textContent = flag;
                document.getElementById('selectedCode').textContent = `+${code}`;
                document.getElementById('countryCodeInput').value = `+${code}`;

                const dropdown = new bootstrap.Dropdown(document.getElementById('countryDropdown'));
                dropdown.hide();
            });
        });

        // Handle phone form submission
        document.getElementById('phoneForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const phoneNumber = formData.get('phone_number');
            const countryCode = formData.get('country_code');

            // Show loading state
            const sendButton = document.getElementById('sendOtpButton');
            sendButton.disabled = true;
            sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

            // Send OTP via AJAX
            fetch("{{ route('send.otp') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status) {
                    // Show OTP modal
                    const fullPhone = countryCode + phoneNumber;
                    document.getElementById('hiddenPhoneNumber').value = fullPhone;
                    document.getElementById('userPhone').textContent = fullPhone;
                    
                    const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                    otpModal.show();
                    
                    // Start countdown timer
                    startCountdown();
                    
                    // Focus first OTP input
                    document.getElementById('otp-0').focus();
                } else {
                    taostr.error(data.message || 'Failed to send OTP');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending OTP');
            })
            .finally(() => {
                sendButton.disabled = false;
                sendButton.textContent = "{{ __('site.continue') }}";
            });
        });

        // Handle OTP form submission
        // document.getElementById('otpForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
            
        //     const verifyButton = document.getElementById('verifyOtpButton2');
        //     verifyButton.disabled = true;
        //     verifyButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verifying...';

        //     fetch("{{ route('verify.otp') }}", {
        //         method: 'POST',
        //         headers: {
        //             'Accept': 'application/json',
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //         },
        //         body: new FormData(this)
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.status) {
        //             window.location.href = data.redirect || "{{ route('home') }}";
        //         } else {
        //             alert(data.message || 'Invalid OTP');
        //             // Clear OTP fields
        //             for (let i = 0; i < 4; i++) {
        //                 document.getElementById(`otp-${i}`).value = '';
        //             }
        //             document.getElementById('otp-0').focus();
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //         alert('An error occurred while verifying OTP');
        //     })
        //     .finally(() => {
        //         verifyButton.disabled = false;
        //         verifyButton.textContent = "{{ __('auth.verify') }}";
        //     });
        // });

        // Resend OTP functionality
        document.getElementById('resendOtpButton').addEventListener('click', function() {
            const resendButton = this;
            resendButton.disabled = true;
            
            fetch("{{ route('resend.otp') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    phone_number: document.getElementById('hiddenPhoneNumber').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    startCountdown();
                } else {
                    taostr.error(data.message || 'Failed to resend OTP');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                taostr.error('An error occurred while resending OTP');
            });
        });

        // OTP input handling functions
        function handleOtpInput(input, index) {
            if (input.value.length === 1) {
                if (index < 3) {
                    document.getElementById(`otp-${index + 1}`).focus();
                }
            }
            updateFullOtp();
        }

        function handleOtpKeyDown(event, index) {
            if (event.key === 'Backspace' && event.target.value === '' && index > 0) {
                document.getElementById(`otp-${index - 1}`).focus();
            }
            updateFullOtp();
        }

        function handleOtpPaste(event) {
            event.preventDefault();
            const pasteData = event.clipboardData.getData('text').trim();
            
            if (/^\d{4}$/.test(pasteData)) {
                for (let i = 0; i < 4; i++) {
                    document.getElementById(`otp-${i}`).value = pasteData[i] || '';
                }
                updateFullOtp();
                document.getElementById('otp-3').focus();
            }
        }

        function updateFullOtp() {
            let fullOtp = '';
            for (let i = 0; i < 4; i++) {
                fullOtp += document.getElementById(`otp-${i}`).value || '';
            }
            document.getElementById('fullOtpInput').value = fullOtp;
        }

        function startCountdown() {
            const resendButton = document.getElementById('resendOtpButton');
            const timerElement = document.getElementById('countdown');
            let countdown = 60;
            
            resendButton.disabled = true;
            document.getElementById('resendTimer').style.display = 'inline';
            
            const countdownInterval = setInterval(() => {
                countdown--;
                timerElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    resendButton.disabled = false;
                    document.getElementById('resendTimer').style.display = 'none';
                }
            }, 1000);
        }
    });
</script>
@endsection