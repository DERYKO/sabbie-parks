<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reservations = Reservation::where('user_id',$request->user()->id)->whereDate('end', '>', now())->get();
        return response()->json($reservations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Reservation::create([
            'user_id' => $request->user()->id,
            'parking_spot_id' => $request->parking_spot_id,
            'registration_number' => $request->registration_number,
            'color' => $request->color,
            'user_vehicle_id' => $request->user_vehicle_id,
            'cost_price' => $request->cost_price,
            'start' => Carbon::parse($request->start)->toDateTime(),
            'end' => Carbon::parse($request->end)->toDateTime()
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
        $reservation = Reservation::findOrfail($id);
        return response()->json($reservation);
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
        $reservation = Reservation::findOrfail($id);
        $reservation->update([
            'registration_number' => $request->registration_number,
            'color' => $request->color,
            'user_vehicle_id' => $request->user_vehicle_id,
            'cost_price' => $request->cost_price,
        ]);
        return response()->json($reservation);
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
