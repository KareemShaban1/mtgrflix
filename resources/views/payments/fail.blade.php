@extends('website.layout.master')

@section('title')
عملية الدفع - فشلت
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <!-- Failure Icon -->
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#dc3545" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                        </svg>
                    </div>

                    <!-- Failure Message -->
                    <h2 class="mb-3 text-danger">فشلت عملية الدفع</h2>
                    <!-- <p class="lead">عذراً، لم يتم إكمال عملية الدفع بنجاح</p> -->
                    <p class="lead"> يرجى التأكد من صحة معلومات البطاقة وأن يكون بها رصيد كافٍ، ثم المحاولة مرة أخرى.
                    </p>
                    <p class="lead"> في حال تكرار المشكله تواصل معنا عبر الواتساب اضغط هنا
                        <a href="https://wa.me/966551200896" class="btn btn-primary">
                            <i class="bx bxl-whatsapp text-success fa-3x"></i>
                        </a>
                    </p>



                    <!-- Redirect Notice -->
                    <p class="mt-4">سيتم إعادتك إلى الصفحة الرئيسية خلال <span id="countdown">10</span> ثوانٍ...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let seconds = 10;
    const countdownEl = document.getElementById('countdown');

    const interval = setInterval(() => {
        seconds--;
        countdownEl.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = "{{ route('home') }}";
        }
    }, 1000);
</script>
@endsection