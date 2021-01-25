<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\ParkingSpot;
use GuzzleHttp\Client;
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
        $client = new Client();
        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->latitude . ',' . $request->longitude . '&key=AIzaSyAnRNSn36QJmZmcocAkuAcTjYG_NhmjoNQ');
        $response = $response->getBody()->getContents();
        $result = json_decode($response);
        $level1 = collect($result->results)->reverse()->filter(function ($item) {
            return collect($item->types)->contains('administrative_area_level_1');
        })->first()->formatted_address;
        $level2 = collect($result->results)->reverse()->filter(function ($item) {
            return collect($item->types)->contains('administrative_area_level_2');
        })->first()->formatted_address;
        $level3 = collect($result->results)->reverse()->filter(function ($item) {
            return collect($item->types)->contains('administrative_area_level_3');
        })->first()->formatted_address;
//        $level4 = collect($result->results)->reverse()->filter(function ($item) {
//            return collect($item->types)->contains('administrative_area_level_4');
//        })->first()->formatted_address;
        $spots = ParkingSpot::whereHas('pricing')
//            ->whereHas('level3', function ($q) use ($level3) {
//                $q->where('formatted_address', 'like', '%' . $level3 . '%');
//            })
            ->whereHas('level3', function ($q) use ($level3) {
                $q->where('formatted_address', 'like', '%' . $level3 . '%');
            })
            ->where('status','vacant')
            ->with('client:id,name,logo', 'pricing:id,parking_spot_id,cost_price')
            ->get(['id', 'client_id', 'parking_spot_code', 'land_mark', 'latitude', 'longitude']);
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
        $spot = ParkingSpot::with('feature:id,parking_spot_id,security_id','feature.security:id,icon,name','level3','client','allowed.vehicle_type','pricing')->findOrFail($id);
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
        $spot->update($request->only('parking_spot_code', 'status', 'latitude', 'longitude', 'land_mark'));
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
