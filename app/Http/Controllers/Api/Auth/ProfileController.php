<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = User::with(
            'vehicles:id,registration_no,color',
            'card_details:id,card_type,card_number,holders_name,cvs_number,expiry_date',
            'booking:id,parking_spot_id,expiry_time,inconvenience_fee,user_vehicle_id',
            'booking.parking_spot:id,client_id,parking_spot_code,client_id,status,land_mark,latitude,longitude',
            'booking.user_vehicle:id,registration_no,color',
            'booking.parking_spot.client:id,logo,name',
            'reservation:id,parking_spot_id,registration_number,color,user_vehicle_id,start,end,cost_price',
            'reservation.user_vehicle:id,registration_number,color',
            'reservation.parking_spot:id,client_id,parking_spot_code,client_id,status,land_mark,latitude,longitude',
            'reservation.parking_spot.client:id,logo,name'
        )->select(['title','avatar','id', 'first_name', 'last_name', 'email', 'phone_number'])->findOrfail($request->user()->id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        if ($request->has('profile')){
            $image = $request->profile['image'];
            $name = $request->profile['name'];
            $realImage = base64_decode($image);
            Storage::disk('public')->put($name,$realImage);
            $request['avatar'] = $name;
        }
        $user = User::findOrfail($request->user()->id);
        $user->update($request->only('avatar','title', 'first_name', 'last_name', 'email'));
        return response()->json(['user' => $user, 'message' => 'success']);
    }
}
