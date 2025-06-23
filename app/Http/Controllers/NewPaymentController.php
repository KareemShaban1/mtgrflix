<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\MyFatoorahPaymentService;
use App\Interfaces\PaymentGatewayInterface;

class NewPaymentController extends Controller
{








    public function success()
    {
        return view('payments.success');
    }
    public function failed(Request $request)
    {
        $response = new MyFatoorahPaymentService();
        return $response->handle_error($request);
    }

    public function initiatePaymentSession()
    {
        // dd (Session::all());
        $base_url = env('MYFATOORAH_BASE_URL');
        $api_key = env("MYFATOORAH_API_KEY");

        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ];

        $body = [
            "CustomerIdentifier" => auth()->user()->name,
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post("$base_url/InitiateSession", [
            'headers' => $headers,
            'json' => $body,
        ]);

        $result = json_decode($response->getBody(), true);
        // dd($result);
        if ($result['IsSuccess']) {
            return [
                'sessionId' => $result['Data']['SessionId'],
                'CountryCode' => $result['Data']['CountryCode'],


            ];
        } else {
            throw new \Exception("Failed to initiate payment session");
        }
    }


    public function webhook(Request $request)
    {
        \Log::info('Webhook received', $request->all());
    }
}
