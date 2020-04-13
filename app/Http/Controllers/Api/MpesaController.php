<?php

namespace App\Http\Controllers\Api;

use App\Collection;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{

    //

    public function generateToken()
    {
        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $mpesa_env = env('MPESA_ENV');

        //check if both keys are set
        if (!$consumer_key || !$consumer_secret) {
            echo 'No consumer key or consumer secret is defined in the .env file';
        } else {

            //else perform token generation
            if ($mpesa_env == 'sandbox') {
                $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
            } elseif ($mpesa_env == 'live') {
                $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
            } else {
                echo 'Mpesa environment is undefined in .env file. Set your environment as either sandbox or live';
            }
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials)); //setting a custom header
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $curl_response = curl_exec($curl);

            return json_decode($curl_response)->access_token;


        }


    }

    public function lipa_na_mpesa(Request $request)
    {
        Collection::create([
            'client_id' => $request->client_id,
            'parking_spot_id' => $request->parking_spot_id,
            'payment_type' => 'Mpesa',
            'amount' => $request->amount,
            'balance' => $request->amount,
            'partyA' => $request->user()->phone_number,
            'partyB' => 174379,
            'status' => false,
        ]);
        $user = User::findOrfail($request->user()->id);
        $access_token = self::generateToken();
        $BusinessShortCode = 174379;
        $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = $request->input('amount');
        $PartyA = substr($request->user()->phone_number,1);
        $PartyB = 174379;
        $PhoneNumber = substr($request->user()->phone_number,1);
        $CallBackURL = 'http://159.89.88.97/api/v1/transactions';
        $AccountReference = 'SabbieParks';
        $TransactionDesc = 'Testing';


        $mpesa_env = env('MPESA_ENV');
        if ($mpesa_env == 'sandbox') {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        } elseif ($mpesa_env == 'live') {
            $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        } else {
            return json_encode(['error message' => 'invalid mpesa environment']);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $access_token)); //setting custom header

        $curl_post_data = array(
            //Fill in the request parameters with valid values

            'BusinessShortCode' => $BusinessShortCode,
            'Password' => base64_encode($BusinessShortCode . $Passkey . date("YmdHis")),
            'Timestamp' => date("YmdHis"),
            'TransactionType' => $TransactionType,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc,
        );
        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        return $curl_response;


    }

    public function transaction_logs(Request $request)
    {
        $payload = $request->json()->all();
        Log::info(json_encode($payload));
        if ($payload) {
            $body = $payload['Body'];
            $stkCallback = $body['stkCallback'];
            $MerchantRequestID = $stkCallback['MerchantRequestID'];
            $CheckoutRequestID = $stkCallback['CheckoutRequestID'];
            $ResultCode = $stkCallback['ResultCode'];
            $ResultDesc = $stkCallback['ResultDesc'];
            $CallbackMetadata = $stkCallback['CallbackMetadata'];
            $Item = $CallbackMetadata['Item'];
            $data = [];
            foreach ($Item as $paymentItem) {
                $Name = $paymentItem['Name'];
                switch ($Name) {
                    case 'Amount':
                        $data['amount'] = $paymentItem['Value'];
                        break;
                    case 'MpesaReceiptNumber':
                        $data['transaction_id'] = $paymentItem['Value'];
                        break;
                    case 'Balance':
                        $Balance = 'Not Available';
                        break;
                    case 'TransactionDate':
                        $data['date'] = $paymentItem['Value'];
                        break;
                    case 'PhoneNumber':
                        $data['phone_number'] = $paymentItem['Value'];
                        break;
                }
            }
            $collection = Collection::where('partyA', $data['phone_number'])->where('partyB', 174379)->where('amount', $data['amount'])->latest()->where('status', false)->first();
            $collection->update([
                'merchantRequestId' => $MerchantRequestID,
                'checkoutRequestId' => $CheckoutRequestID,
                'status' => $ResultCode == 200 ? true : false,
                'receipt_no' => Carbon::now()->getTimestamp()
            ]);
            return response()->json(['message' => 'Transaction complete', 'status' => 'success']);
        }
        return response()->json([
            'No data received'
        ]);
    }
}
