<?php

namespace App\Http\Controllers\Api;

use App\Booking;
use App\Collection;
use App\Http\Controllers\Controller;
use App\Jobs\PaymentStatusSuccess;
use App\ParkingSpot;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->latest()->first();
        return response()->json($wallet);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */

    public function lipaNaWallet(Request $request)
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->latest()->first();
        Wallet::create([
            'user_id' => $request->user()->id,
            'transaction_type' => 'debit',
            'debit' => $request->amount,
            'credit' => 0.00,
            'balance' => $wallet->balance - $request->amount
        ]);
        $c = Collection::create([
            'user_vehicle_id' => $request->user_vehicle_id,
            'client_id' => $request->client_id,
            'parking_spot_id' => $request->parking_spot_id,
            'payment_type' => 'Wallet',
            'amount' => $request->amount,
            'partyA' => $request->user()->phone_number,
            'partyB' => 174379,
            'status' => 1,
        ]);
        $parking_spot = ParkingSpot::findOrfail($request->parking_spot_id);
        $parking_spot->update([
            'status' => 'Occupied'
        ]);
        Booking::create([
            'user_id' => $request->user()->id,
            'parking_spot_id' => $request->parking_spot_id,
            'user_vehicle_id' => $request->user_vehicle_id,
            'expiry_time' => 30,
            'inconvenience_fee' => 50
        ]);
        dispatch_now(new PaymentStatusSuccess("Amount deducted from wallet successfully", $request->user(), 30, 50, $parking_spot));
        return \response()->json(['message' => 'Wallet balance deducted successfully'], 200);
    }

    public function store(Request $request)
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->latest()->first();
        $value = Wallet::create([
            'user_id' => $request->user()->id,
            'transaction_type' => $request->transaction_type,
            'debit' => $request->transaction_type == 'debit' ? $request->debit : 0.0,
            'credit' => $request->transaction_type == 'credit' ? $request->credit : 0.0,
            'balance' => $request->transaction_type == 'credit' ? $wallet->balance + $request->credit : $wallet->balance - $request->debit
        ]);
        return response()->json($value);

    }

    public function lipaNaMpesa(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $wallet = Wallet::findOrFail($id);
        return response()->json($wallet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
