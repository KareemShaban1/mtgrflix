<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تفاصيل الفاتورة</title>
  <style>
    body {
      font-family: 'Tajawal', Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }
    .invoice-container {
      background: #fff;
      max-width: 400px;
      margin: auto;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .headr-top {
      background: #3448da;
      color: white;
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-radius: 8px 8px 0 0;
      font-size: 18px;
      position: relative;
      text-align: center;
    }
    .header .icon {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
    }
    .amount {
      text-align: center;
      margin: 20px 0;
    }
    .amount h2 {
      color: #2196f3;
      margin: 0;
    }
    .amount p {
      background-color: #e8f5e9;
      color: #2e7d32;
      display: inline-block;
      padding: 5px 15px;
      border-radius: 20px;
      margin-top: 10px;
      font-weight: bold;
    }
    .section {
      background-color: #f9f9f9;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
      text-align: center;
    }
    .section.success {
      background-color: #e8f5e9;
      color: #2e7d32;
      font-weight: bold;
    }
    .info {
      margin-top: 10px;
      font-size: 14px;
      text-align: right;
    }
    .info-item {
      margin-bottom: 8px;
    }
    .info-item span {
      font-weight: bold;
    }
    .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #666;
    }
    img.qr {
      display: block;
      margin: 10px auto;
      width: 120px;
      height: 120px;
    }
    .header {
      background: #faf9f9;
      color: #020000;
      padding: 10px;
      margin: 5px 0;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .header-right {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .header-right img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
    }
    .language-btn {
      background: white;
      color: #34a8db;
      border: 1px solid #ccc;
      padding: 5px 10px;
      font-size: 14px;
      border-radius: 5px;
      cursor: pointer;
    }
    .details-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
    }
    .store-info {
      text-align: right;
    }
    .price {
      color: #007bff;
      font-weight: bold;
      font-size: 18px;
      margin: 5px 0;
    }
    .paid {
      background: #d4edda;
      color: #28a745;
      padding: 3px 10px;
      font-size: 14px;
      border-radius: 10px;
      display: inline-block;
    }
    .qr-code {
      text-align: center;
    }
    .qr-code img {
      width: 120px;
      height: 120px;
    }
  </style>
</head>

<body>

  <div class="invoice-container">
    <div class="headr-top">
      تفاصيل الفاتورة
      {{-- <div class="icon">🖨️</div> --}}
    </div>

    <div class="header">
      <div class="header-right">
        <img src="logo.avif" alt="Logo">
        <div>متجر فليكس </div>
      </div>
      <!-- <button class="language-btn">English</button> -->
    </div>

    <div class="details-top">
      <div class="store-info">
        <div class="price">15.00 رس</div>
        <div class="paid">مدفوعة</div>
      </div>
      <div class="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=Example" alt="QR Code">
      </div>
    </div>

    <div class="section success">
      العملية ناجحة <img src="{{ public_path('frontend') }}/assets/image/check.png" alt="" height="20px" width="20px">
    </div>

    <div class="section">
      <div class="info">
        <h2>مصدرة الى</h2>
        <div class="info-item"><span>اسم العميل:</span> أحمد محمد</div>
        <div class="info-item"><span>رقم الجوال:</span> 0551234567</div>
      </div>
    </div>

    <div class="section">
      <h2 style="text-align: center;">تفاصيل المنتجات</h2>
      <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
          <tr style="background-color: #f1f1f1;">
            <th style="padding: 10px; border: 1px solid #ddd;">المنتج</th>
            <th style="padding: 10px; border: 1px solid #ddd;">الكمية</th>
            <th style="padding: 10px; border: 1px solid #ddd;">السعر</th>
            <th style="padding: 10px; border: 1px solid #ddd;">الإجمالي</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="padding: 10px; border: 1px solid #ddd; display: flex; align-items: center; gap: 10px;">
              <img src="logo.avif" alt="صورة المنتج" style="width: 40px; height: 40px; border-radius: 5px;">
              <span>حساب فليكس رسمي شهر</span>
            </td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">2</td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">10 رس</td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">20 رس</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="info">
        <h2> تفاصيل العملية</h2>
        <div class="info-item"><span>رقم الطلب:</span> 276640</div>
        <div class="info-item"><span>مرجع الفاتورة:</span> 2025000211</div>
        <div class="info-item"><span>تاريخ العملية:</span> 25/04/2025 18:39:25</div>
        <div class="info-item"><span>المبلغ المدفوع:</span> 15.00 ريال</div>
        <div class="info-item"><span>طريقة الدفع:</span> فيزا / ماستر</div>
        <div class="info-item"><span>رقم الدفع:</span> 08084766784464456497085</div>
        <div class="info-item"><span>رقم العملية:</span> 276640</div>
        <div class="info-item"><span>رقم المرجع:</span> 511515276640</div>
      </div>
    </div>

    <div class="order">
      <div class="section">
        <div class="info-item" style="display: flex; justify-content: space-between; align-items: center;">
          <strong>مجموع السلة:</strong>
          <span>22 SAR</span>
        </div>
        <div class="info-item" style="display: flex; justify-content: space-between; align-items: center;">
          <strong>إجمالي الطلب:</strong>
          <span>22 SAR</span>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="info">
        <h2>بيانات التواصل</h2>
        <h4>المتجر الالكتروني متجر فليكس</h4>
        <div class="info-item">
          <strong>رقم الواتساب:</strong>
          <a href="https://wa.me/966551200896" target="_blank">  966551200896+</a>
        </div>
        
        <div class="info-item">
          <strong>البريد الإلكتروني:</strong>
          <a href="mailto:mtgrflix@gmail.com">mtgrflix@gmail.com</a>
        </div>
      </div>
    </div>

    <div class="footer">
      شكرا لشرائك من المتجر نتمنى لك يوماً رائعاً
    </div>

  </div>

</body>
</html>
