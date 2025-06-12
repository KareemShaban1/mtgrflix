<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\MyFatoorahPaymentService;
use App\Interfaces\PaymentGatewayInterface;

class PaymentController extends Controller
{

    // use MyFatoorahPaymentService;
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {

        $this->paymentGateway = $paymentGateway;
    }


    public function paymentProcess(Request $request)
    {

        return $this->paymentGateway->sendPayment($request);
    }

    public function callBack(Request $request)
    {
        $response = $this->paymentGateway->callBack($request);
        return $response;
        
    }



    public function success()
    {
        return view('payments.success');
    }
    public function failed(Request $request)
    {
        $response=new MyFatoorahPaymentService();
        return $response->handle_error($request);
    }
}
