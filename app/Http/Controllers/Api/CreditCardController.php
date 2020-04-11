<?php

namespace App\Http\Controllers\Api;

use App\CardDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreditCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $card = CardDetail::with('user:id,first_name,last_name')->where('user_id', $request->user()->id)->first();
        return response()->json($card);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $card = CardDetail::create([
            'user_id' => $request->user()->id,
            'card_type' => $request->card_type,
            'card_number' => $request->card_number,
            'holders_name' => $request->holders_name,
            'cvs_number' => $request->cvs_number,
            'expiry_date' => $request->expiry_date
        ]);
        return response()->json($card);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $card = CardDetail::findOrfail($id);
        $card->update($request->only('card_type', 'card_number', 'holders_name', 'cvs_number', 'expiry_date'));
        return response()->json($card);
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
