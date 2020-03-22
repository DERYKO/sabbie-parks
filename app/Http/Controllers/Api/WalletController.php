<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->get();
        return response()->json($wallet);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $wallet = Wallet::findOrFail($id);
        return response()->json($wallet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
