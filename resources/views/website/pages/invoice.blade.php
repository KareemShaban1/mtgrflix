<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flix invoice </title>
    <style>
        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .ltr-number {
            direction: ltr;
            unicode-bidi: embed;
        }

        .invoice-container {
            background: #fff;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        /* Success state */
        .section.success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-right: 4px solid #4caf50;
            /* RTL: border-right instead of border-left */
            padding: 12px;
            margin: 15px 0;
            text-align: right;
            /* RTL alignment */
            direction: rtl;
        }

        /* Pending state */
        .section.pending {
            background-color: #fff8e1;
            color: #ff8f00;
            border-right: 4px solid #ffc107;
            padding: 12px;
            margin: 15px 0;
            text-align: right;
            direction: rtl;
        }

        /* Failed/Not completed state */
        .section.failed {
            background-color: #ffebee;
            color: #c62828;
            border-right: 4px solid #f44336;
            padding: 12px;
            margin: 15px 0;
            text-align: right;
            direction: rtl;
        }
    </style>
</head>

<body>

    @php
        $currency = session('currency', 'SAR');
        $symbol = session('symbol');
    @endphp
    <div class="invoice-container">
        <div class="headr-top">
            تفاصيل الفاتورة
        </div>
        <br>



        <div style="text-align: right;">
            <div style="display: inline-block; vertical-align: middle; margin-right: 10px;">
                متجر فليكس
            </div>
            <div style="display: inline-block; vertical-align: middle;">
                <img src="https://mtgrflix.com/frontend/assets/image/logo.png" alt="Logo"
                    style="max-width: 60px; display: inline-block; vertical-align: middle;">
            </div>

        </div>

        <div style="clear: both;">
        </div>


        <div class="details-top">
            <div class="store-info" style="display: table; width: 100%;">

                <!-- QR Code -->
                <div style="display: table-cell; width: 1px; padding-left: 10px; vertical-align: middle;">
                    <div class="qr-code">
                        <img src="{{ public_path('storage/' . $order->qr_code) }}" alt="QR Code">
                    </div>
                </div>
                <!-- Price and Paid Status -->
                <div style="display: table-cell; vertical-align: middle; ">
                    <div class="price text-primary d-inline" style="font-size: 18px; font-weight: bold;">
                        {{ number_format($order->grand_total * $order->exchange_rate, 2) }}
                    </div>
                    <div>{{ $order->currency->symbol }}</div>
                    <div class="paid" style="margin-top: 5px;">
                        {{ $order->payment_status == 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                    </div>
                </div>


            </div>


            <div class="section {{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'pending' : 'failed') }}"
                style="text-align: center;">
                @if ($order->payment_status === 'paid')
                    عملية ناجحة <img src="https://mtgrflix.com/frontend/assets/image/check.png" alt=""
                        height="20px" width="20px">
                @elseif($order->payment_status === 'pending')
                    عملية معلقة ⏳
                @else
                    عملية غير مكتملة ❌
                @endif
            </div>

            <div class="section">
                <div class="info">
                    <h2>مصدرة الى</h2>
                    <div class="info-item">{{ $order->user->name }}<span>اسم العميل:</span></div>
                    <div class="info-item">{{ $order->user->mobile }}<span>رقم الجوال:</span></div>
                </div>
            </div>

            <div class="section">
                <h2 style="text-align: center;">تفاصيل المنتجات</h2>
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background-color: #f1f1f1;">
                            <th style="padding: 10px; border: 1px solid #ddd;">الإجمالي</th>
                            <th style="padding: 10px; border: 1px solid #ddd;">السعر</th>
                            <th style="padding: 10px; border: 1px solid #ddd;">الكمية</th>
                            <th style="padding: 10px; border: 1px solid #ddd;">المنتج</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <!-- الإجمالي -->
                                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                    {{ $order->currency->symbol }}
                                </td>

                                <!-- السعر -->
                                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                    {{ $order->currency->symbol }}
                                </td>

                                <!-- الكمية -->
                                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                    {{ $item->quantity }}
                                </td>

                                <!-- المنتج -->
                                <td style="padding: 10px; border: 1px solid #ddd;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        {{-- <img src="{{ public_path('storage/' . $item->product->images) }}"
                                            alt="صورة المنتج" style="width: 40px; height: 40px; border-radius: 5px;"> --}}
                                        <span>{{ $item->product->name }}</span>
                                        <img src="https://mtgrflix.com/storage/{{ $item->product->images }}"
                                            style="width: 30px; height: 30px; border-radius: 5px; float: right;" >

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="clear: both;">
            </div>


            <div class="section">
                <div class="info">
                    <h2>تفاصيل العملية</h2>
                    <div class="info-item">{{ $order->number }}<span>رقم الطلب:</span></div>
                    <div class="info-item">{{ $order->payment?->invoice_id ?? 'N/A' }}<span>مرجع الفاتورة:</span></div>
                    <div class="info-item">{{ $order->created_at->format('d/m/Y H:i:s') }}<span>تاريخ العملية:</span>
                    </div>
                        
                    <div class="info-item">{{ $order->grand_total * $order->exchange_rate }}<span> المبلغ المدفوع:</span></div>
                    {{-- <div class="info-item">{{ ucfirst($order->payment_method) }}<span>بوابة الدفع:</span></div> --}}
                    <div class="info-item">
                        {{ isset($order->payment['data']['InvoiceTransactions'][0]['PaymentGateway']) ? ucfirst($order->payment['data']['InvoiceTransactions'][0]['PaymentGateway']) : '' }}
                        <span>طريقة الدفع:</span>
                    </div>
                    <div class="info-item">
                        {{ data_get($order, 'payment.data.InvoiceTransactions.0.ReferenceId') ?? '' }}
                        <span>رقم المرجع:</span>
                    </div>

                    <div class="info-item">{{ $order->payment_status ?? 'N/A' }}<span>حالة الدفع:</span></div>
                </div>
            </div>

            <div class="order">
                <div class="section">
                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center;">
                        {{ $order->currency->symbol }} <span
                            class="ltr-number">{{ number_format($order->grand_total * $order->exchange_rate, 2, '.', ',') }}</span>
                        <strong>مجموع السلة: </strong>

                    </div>

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            {{ $order->currency->symbol }}
                            <span
                                class="ltr-number">{{ number_format($order->grand_total * $order->exchange_rate, 2, '.', ',') }}</span>
                        </span>
                        <strong>إجمالي الطلب:</strong>
                    </div>
                </div>

            </div>

            <div class="section">
                <div class="info">
                    <h2>بيانات التواصل</h2>
                    <h4>المتجر الالكتروني متجر فليكس</h4>
                    <div class="info-item">
                        <a href="https://wa.me/966551200896" target="_blank">966551200896+</a>
                        <span>رقم الواتساب:</span>
                    </div>

                    <div class="info-item">
                        <a href="mailto:mtgrflix@gmail.com">mtgrflix@gmail.com</a>
                        <span>البريد الإلكتروني:</span>
                    </div>
                </div>
            </div>

            <div class="footer">
                شكرا لشرائك من المتجر نتمنى لك يوماً رائعاً
            </div>
        </div>
</body>


</html>
