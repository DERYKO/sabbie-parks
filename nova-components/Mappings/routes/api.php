<?php

use App\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });

Route::get('/spaces',function (){
    $spots = ParkingSpot::with('client:id,name')->get(['client_id','parking_spot_code', 'land_mark', 'latitude', 'longitude']);
    return response()->json($spots);
});
