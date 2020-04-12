<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\UserVehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vehicles = UserVehicle::with('vehicle_type:id,icon,name')->where('user_id', $request->user()->id)->get();
        return response()->json($vehicles);
    }

    /**$v
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        UserVehicle::create([
            'user_id' => $request->user()->id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'registration_no' => $request->registration_no,
            'color' => $request->color,
            'model_type' => $request->model_type
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
        $vehicle = UserVehicle::findOrfail($id);
        return response()->json($vehicle);
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
        $vehicle = UserVehicle::findOrfail($id);
        $vehicle->update([
            'vehicle_type_id' => $request->vehicle_type_id,
            'registration_no' => $request->registration_no,
            'color' => $request->color,
            'model_type' => $request->model_type
        ]);
        return $this->index($request);
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
