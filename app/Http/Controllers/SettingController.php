<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SettingController extends Controller
{
    // public function setCurrency(Request $request)
    // {
    //     $currency = Currency::where('code', $request->currency)->first();
    //     $country = Country::where('currency', $currency->code)->first();
    //     if ($currency) {
    //         session([
    //             'currency' => $currency->code,
    //             'rate' => $currency->exchange_rate,
    //             'symbol' => $currency->symbol,
    //             // 'country'  => $country->code ?? '966',
    //         ]);

    //         return response()->json(['success' => true]);
    //     }

    //     return response()->json(['success' => false], 400);
    // }

    public function setCurrency(Request $request)
    {
        session()->forget('currency'); // Clear old one if needed

        $currencyCode = $request->input('currency');
        $currency = Currency::where('code', $currencyCode)->first();

        if ($currency) {
            session([
                'currency' => $currency->code,
                'rate'     => $currency->exchange_rate,
                'symbol'   => $currency->symbol,
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Currency not found'], 404);
    }

    public function callback2()
    {
        $data = [
            'client_id'     => '9ced3325-9102-4ba2-8408-4cd323881987',
            'client_secret' => '57a5896530c028a08c43003b20625577',
            'code'          =>  request('code'),
            'grant_type'    => 'authorization_code',
            'scope'         => 'offline_access',
            'redirect_uri'  => route('salla.callback'),
            'state'         => request('state'),
        ];

        $response = Http::asForm()->post("https://accounts.salla.sa/oauth2/token", $data);

        $status = $response->status();

        if ($status == 200) {

            $body = $response->body();

            $body = json_decode($body);
            $data = json_decode($response->body(), true); // now it's an array
            Log::info('Salla response:', $data);            // find the first record or create a new one



            return true;
        }

        // dd('Token Not Created');
        return false;
    }

    public function callback(Request $request)
    {

        Log::debug('Webhook Request Body: ', $request->all());
        $securityStrategy = $request->header('X-Salla-Security-Strategy');

        if ($securityStrategy === 'Token') {
            $token = $request->header('Authorization');
            $expectedToken = env('SALLA_WEBHOOK_TOKEN', '79cf10e489a837b1a2f0853488ccb7b6');

            if ($token !== $expectedToken) {
                Log::warning('Unauthorized webhook request. Invalid token.', ['provided_token' => $token]);
                return response('Unauthorized', 401);
            }
        }

        $payload = $request->all();
        $event = $payload['event'];

        Log::info("Received Salla webhook: $event", $payload);

        switch ($event) {
            case 'order.created':
                $this->handleOrderCreated($payload);
                break;

            case 'order.updated':
                $this->handleOrderUpdated($payload);
                break;

            case 'app.store.authorize':
                $this->handleAppStoreAuthorize($payload);
                break;
            default:
                Log::warning("Unhandled webhook event received: $event", $payload);
                break;
        }

        return response('Webhook processed successfully', 200);
    }


    private function handleAppStoreAuthorize($payload)
    {
        // try {
        //     $data = $payload['data'];
        //     $sallaSetting = SallaSetting::first();
        //     if ($sallaSetting) {
        //         $sallaSetting->update([
        //             'token'         => $data['access_token'],
        //             'refresh_token' => $data['refresh_token'],
        //             'expires_in'    => Carbon::now()->addSeconds($data['expires']),
        //         ]);
        //     } else {
        //         SallaSetting::create([
        //             'token'         => $data['access_token'],
        //             'refresh_token' => $data['refresh_token'],
        //             'expires_in'    => Carbon::now()->addSeconds($data['expires']),
        //         ]);
        //     }
        // } catch (\Exception $e) {
        //     Log::error('Error while handling app store authorization', [
        //         'error_message' => $e->getMessage(),
        //     ]);
        // }
    }
}
