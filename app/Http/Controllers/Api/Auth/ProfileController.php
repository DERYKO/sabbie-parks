<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()
            ->with(
                'wallet:id,balance',
                'vehicles:id,registration_number,color',
                'card_details:id,card_type,card_number,holders_name,cvs_number,expiry_date',
                'bookings:id,parking_spot_id,expiry_time,inconvenience_fee,registration_number,color,user_vehicle_id,scheduled_book_time',
                'bookings.spot:id,client_id,parking_spot_code,client_id,status,land_mark,latitude,longitude',
                'bookings.vehicle:id,registration_number,color',
                'bookings.spot.client:id,logo,name',
                'reservations:id,parking_spot_id,registration_number,color,user_vehicle_id,start,end,cost_price',
                'reservations.vehicle:id,registration_number,color',
                'reservations.spot:id,client_id,parking_spot_code,client_id,status,land_mark,latitude,longitude',
                'reservations.spot.client:id,logo,name'
            );
        return response()->json($user);
    }
}
