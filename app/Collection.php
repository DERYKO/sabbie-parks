<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['user_vehicle_id', 'client_id', 'parking_spot_id', 'payment_type', 'merchantRequestId', 'checkoutRequestId', 'amount', 'ResultDesc', 'partyA', 'partyB', 'status', 'receipt_no'];


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    public function user_vehicle(){
        return $this->belongsTo(UserVehicle::class,'user_vehicle_id','id');
    }

    public function parking_spot()
    {
        return $this->belongsTo(ParkingSpot::class, 'parking_spot_id', 'id');
    }

}
