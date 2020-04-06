<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\ParkingSpot;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $spots = ParkingSpot::with('client:id,name')->get(['id','parking_spot_code', 'land_mark', 'latitude', 'longitude']);
        return response()->json($spots);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        ParkingSpot::create([
            'client_id' => $request->user()->client_id,
            'parking_spot_code' => $request->parking_spot_code,
            'status' => 'vacant',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'land_mark' => $request->landmark
        ]);
        return $this->index($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $spot = ParkingSpot::findOrFail($id);
        return response()->json($spot);
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
        $spot = ParkingSpot::findOrFail($id);
        $spot->update($request->only('parking_spot_code','status','latitude','longitude','land_mark'));
        return  $this->index($request);
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
