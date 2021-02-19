<?php

namespace App\Http\Controllers\Api;

use App\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bookings = Booking::FilterBy($request->all())->with('parking_spot', 'user_vehicle','user_vehicle.vehicle_type:id,icon,name')
//            ->where('user_id', $request->user()->id)
            ->get();
        return response()->json($bookings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Booking::create([
            'user_id' => $request->user()->id,
            'parking_spot_id' => $request->parking_spot_id,
            'expiry_time' => $request->expiry_time,
            'registration_number' => $request->registration_number,
            'color' => $request->color,
            'user_vehicle_id' => $request->user_vehicle_id,
            'inconvenience_fee' => $request->inconvenience_fee
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
        $booking = Booking::findOrfail($id);
        return response()->json($booking);
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
        $booking = Booking::findOrfail($id);
        $booking->update([
            'expiry_time' => $request->expiry_time,
            'registration_number' => $request->registration_number,
            'color' => $request->color,
            'user_vehicle_id' => $request->user_vehicle_id,
            'inconvenience_fee' => $request->inconvenience_fee
        ]);
        return response()->json($booking);
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
