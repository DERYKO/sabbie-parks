<?php

namespace App\Http\Controllers\Api;

use App\Allowed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\ApiOperations\All;

class AllowedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allowed = Allowed::with('vehicle_type')->get();
        return response()->json($allowed);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Allowed::create([
            'parking_spot_id' => $request->parking_spot_id,
            'vehicle_type_id' => $request->vehicle_type_id
        ]);
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $allowed = Allowed::findOrfail($id);
        return response()->json($allowed);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
