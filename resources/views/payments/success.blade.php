@extends('website.layout.master')

@section('title')
    تأكيد الدفع - تم بنجاح
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                    </div>
                    
                    <!-- Success Message -->
                    <h2 class="mb-3 text-success">تمت عملية الدفع بنجاح</h2>
                    <p class="lead">شكراً لك على ثقتك بنا، لقد تم استلام طلبك بنجاح</p>
                    
                    {{-- <!-- Order Details -->
                    <div class="bg-light p-4 rounded my-4 text-start">
                        <h5 class="mb-3">تفاصيل الطلب</h5>
                        <p><strong>رقم الطلب:</strong> #{{ $order->id ?? 'N/A' }}</p>
                        <p><strong>المبلغ المدفوع:</strong> {{ number_format($amount ?? 0, 2) }} ر.س</p>
                        <p><strong>تاريخ الدفع:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
                        <p><strong>طريقة الدفع:</strong> {{ $payment_method ?? 'بطاقة ائتمانية' }}</p>
                    </div>
                    
                    <!-- Next Steps -->
                    <div class="alert alert-info mt-4">
                        <h6>ماذا بعد؟</h6>
                        <p class="mb-0">سيتم إرسال تفاصيل الطلب إلى بريدك الإلكتروني. يمكنك تتبع حالة طلبك من خلال حسابك.</p>
                    </div> --}}
                    
                    <!-- Action Buttons -->
                    {{-- <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary px-4">
                            العودة إلى الصفحة الرئيسية
                        </a>
                        <a href="{{ route('orders.show', $order->id ?? '') }}" class="btn btn-outline-secondary px-4">
                            عرض تفاصيل الطلب
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection