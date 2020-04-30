<?php

namespace App\Http\Controllers\Api;

use App\Booking;
use App\Collection;
use App\Jobs\PaymentStatusFail;
use App\Jobs\PaymentStatusSuccess;
use App\Nova\ParkingSpot;
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
        $c = Collection::create([
            'user_vehicle_id' => $request->user_vehicle_id,
            'client_id' => $request->client_id,
            'parking_spot_id' => $request->parking_spot_id,
            'payment_type' => 'Mpesa',
            'amount' => $request->amount,
            'partyA' => $request->user()->phone_number,
            'partyB' => 174379,
            'status' => 1,
        ]);
        $access_token = self::generateToken();
        $BusinessShortCode = 174379;
        $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = $request->input('amount');
        $PartyA = substr($request->user()->phone_number, 1);
        $PartyB = 174379;
        $PhoneNumber = substr($request->user()->phone_number, 1);
        $CallBackURL = 'http://159.89.88.97/api/v1/transactions/'.$c->id;
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

    public function transaction_logs(Request $request,$id)
    {
        $request = $request['Body'];
        $collection = Collection::findOrfail($id);
        $user = User::where('phone_number', $collection->partyA)->first();
        if ($request['stkCallback']['ResultCode'] == 1) {
            $collection->update([
                'merchantRequestId' => $request['stkCallback']['MerchantRequestID'],
                'checkoutRequestId' => $request['stkCallback']['CheckoutRequestID'],
                'ResultDesc' => $request['stkCallback']['ResultDesc'],
                'status' => $request['stkCallback']['ResultCode']
            ]);
            $this->dispatch(new PaymentStatusFail($request['stkCallback']['ResultDesc'],$user));

        }elseif ($request['stkCallback']['ResultCode'] == 1032){
            $collection->update([
                'merchantRequestId' => $request['stkCallback']['MerchantRequestID'],
                'checkoutRequestId' => $request['stkCallback']['CheckoutRequestID'],
                'ResultDesc' => $request['stkCallback']['ResultDesc'],
                'status' => $request['stkCallback']['ResultCode']
            ]);
            $this->dispatch(new PaymentStatusFail($request['stkCallback']['ResultDesc'],$user));
        }
        elseif ($request['stkCallback']['ResultCode'] == 0) {
            $collection->update([
                'merchantRequestId' => $request['stkCallback']['MerchantRequestID'],
                'checkoutRequestId' => $request['stkCallback']['CheckoutRequestID'],
                'ResultDesc' => $request['stkCallback']['ResultDesc'],
                'status' => $request['stkCallback']['ResultCode'],
                'receipt_no' => 78999909
            ]);
            $parking_spot = \App\ParkingSpot::findOrfail($collection->parking_spot_id);
            $parking_spot->update([
                'status' => 'Occupied'
            ]);
            Booking::create([
                'user_id' => $user->id,
                'parking_spot_id' => $collection->parking_spot_id,
                'user_vehicle_id' => $collection->user_vehicle_id,
                'expiry_time' => 30,
                'inconvenience_fee' => 50
            ]);
            $this->dispatch(new PaymentStatusSuccess($request['stkCallback']['ResultDesc'],$user,30,50,ParkingSpot::findOrfail($collection->parking_spot_id)));
        }
        return response()->json(['message' => 'Success']);
    }
}
