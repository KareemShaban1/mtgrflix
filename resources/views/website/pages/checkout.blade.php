@extends('website.layout.master')

@section('title')
    الرئيسية
@endsection

@section('css')
<style>
    .checkout{
        direction: rtl;
    }
        .step-indicator {
          display: flex;
          justify-content: center;
          margin-bottom: 30px;
          flex-wrap: wrap;
        }
        .step {
          display: flex;
          align-items: center;
          font-size: 14px;
          color: #6c757d;
        }
        .step.active {
          color: #0d6efd;
          font-weight: bold;
        }
        .step:not(:last-child)::after {
          content: ">";
          margin: 0 10px;
          color: #ccc;
        }
        .card {
          border-radius: 12px;
          box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .sticky-summary {
          position: sticky;
          top: 20px;
        }
        .form-control, .form-select {
          border-radius: 8px;
        }
      </style>
@endsection
@section('content')
    <!-- checkout start -->
 
    <div class="container checkout">
        <div class="row">
    
          <div class="col-lg-8 mb-4">
            <div class="card p-4">
              <h5 class="mb-4">معلومات العميل</h5>
    
              <form>
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" class="form-control" placeholder="أدخل اسمك الكامل" required>
                  </div>
    
                  <div class="col-md-6">
                    <label class="form-label">رقم الجوال</label>
                    <input type="tel" class="form-control" placeholder="مثال: 05xxxxxxxx" required>
                  </div>
    
                  <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" placeholder="you@example.com" required>
                  </div>
    
                  <div class="col-12">
                    <label class="form-label">العنوان</label>
                    <input type="text" class="form-control" placeholder="الشارع، اسم المبنى، إلخ" required>
                  </div>
    
                  <div class="col-md-6">
                    <label class="form-label">المدينة</label>
                    <select class="form-select" required>
                      <option selected disabled>اختر المدينة</option>
                      <option>الرياض</option>
                      <option>جدة</option>
                      <option>الدمام</option>
                      <option>مكة</option>
                    </select>
                  </div>
    
                  <div class="col-md-6">
                    <label class="form-label">الرمز البريدي</label>
                    <input type="text" class="form-control" placeholder="12345" required>
                  </div>
    
                  <div class="col-12">
                    <label class="form-label">ملاحظات إضافية (اختياري)</label>
                    <textarea class="form-control" rows="3" placeholder="اكتب أي ملاحظة تخص الطلب"></textarea>
                  </div>
                </div>
    
                <div class="d-grid gap-2 mt-4">
                  <button type="submit" class="btn btn-primary btn-lg">متابعة إلى الشحن</button>
                </div>
              </form>
            </div>
          </div>
    
          <div class="col-lg-4">
            <div class="card p-4 sticky-summary">
              <h5 class="mb-3">ملخص الطلب</h5>
    
              <div class="d-flex justify-content-between mb-2">
                <span>منتج 1</span>
                <span>100 ر.س</span>
              </div>
    
              <div class="d-flex justify-content-between mb-2">
                <span>منتج 2</span>
                <span>150 ر.س</span>
              </div>
    
              <hr>
    
              <div class="d-flex justify-content-between mb-2">
                <span>الشحن</span>
                <span>20 ر.س</span>
              </div>
    
              <div class="d-flex justify-content-between mb-2">
                <span>الضريبة</span>
                <span>15 ر.س</span>
              </div>
    
              <hr>
    
              <div class="d-flex justify-content-between mb-3 fw-bold">
                <span>الإجمالي</span>
                <span>285 ر.س</span>
              </div>
    
              <button class="btn btn-success w-100">اتمام الطلب</button>
    
            </div>
          </div>
    
        </div>
      </div>
    <!-- checkout end -->
@endsection

@section('script')

@endsection