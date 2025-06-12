@extends('website.layout.master')

@section('title')
    الرئيسية
@endsection

@section('css')

@endsection
@section('content')
<!-- OTP Modal Start -->
<div class="modal fade show" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header border-0 position-relative">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                <button type="button" class="btn position-absolute top-0 end-0 m-3 bg-light" id="backToLogin">
                    <i class="bx bx-arrow-to-right" style="font-size: 24px;"></i>
                </button>
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

                    <button type="submit" class="btn btn-primary w-100 mt-3" >
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
<!-- OTP Modal End -->
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus first OTP input
        document.querySelector('.otp-input').focus();
        
        // Resend OTP functionality
        const resendButton = document.getElementById('resendOtpButton');
        let countdown = 60;
        const timerElement = document.getElementById('countdown');
        
        // Start countdown
        const countdownInterval = setInterval(() => {
            countdown--;
            timerElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                resendButton.disabled = false;
                document.getElementById('resendTimer').style.display = 'none';
            }
        }, 1000);
        
        // Resend OTP handler
        resendButton.addEventListener('click', function() {
            this.disabled = true;
            countdown = 60;
            timerElement.textContent = countdown;
            document.getElementById('resendTimer').style.display = 'block';
            
            // Reset countdown
            clearInterval(countdownInterval);
            const newInterval = setInterval(() => {
                countdown--;
                timerElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(newInterval);
                    resendButton.disabled = false;
                    document.getElementById('resendTimer').style.display = 'none';
                }
            }, 1000);
            
            // AJAX request to resend OTP
            fetch("{{ route('resend.otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    phone_number: document.getElementById('hiddenPhoneNumber').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    toastr.error(data.message || "{{ __('auth.resend_failed') }}");
                }
            });
        });
    });
    
    function moveToNext(input, currentIndex) {
        if (input.value.length === 1) {
            const nextInput = document.querySelector(`.otp-input:nth-child(${currentIndex + 1})`);
            if (nextInput) nextInput.focus();
        }
    }
    
    function moveToPrevious(event, currentIndex) {
        if (event.key === 'Backspace' && event.target.value === '') {
            const prevInput = document.querySelector(`.otp-input:nth-child(${currentIndex - 1})`);
            if (prevInput) prevInput.focus();
        }
    }
    
    function closeModal() {
        window.location.href = "{{ route('login') }}";
    }
</script>

<script>
    function handleOtpInput(input, index) {
        // Auto-move to next input
        if (input.value.length === 1) {
            if (index < 3) {
                document.getElementById(`otp-${index + 1}`).focus();
            }
        }
        updateFullOtp();
    }

    function handleOtpKeyDown(event, index) {
        // Handle backspace
        if (event.key === 'Backspace' && event.target.value === '' && index > 0) {
            document.getElementById(`otp-${index - 1}`).focus();
        }
        updateFullOtp();
    }

    function handleOtpPaste(event) {
        // Handle paste of full OTP
        event.preventDefault();
        const pasteData = event.clipboardData.getData('text').trim();
        
        if (/^\d{4}$/.test(pasteData)) {
            for (let i = 0; i < 4; i++) {
                document.getElementById(`otp-${i}`).value = pasteData[i] || '';
            }
            updateFullOtp();
        }
    }

    function updateFullOtp() {
        // Combine all OTP digits into one string
        let fullOtp = '';
        for (let i = 0; i < 4; i++) {
            fullOtp += document.getElementById(`otp-${i}`).value || '';
        }
        document.getElementById('fullOtpInput').value = fullOtp;
    }
</script>
@endsection