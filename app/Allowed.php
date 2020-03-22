<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowed extends Model
{
    protected $fillable = ['parking_spot_id', 'vehicle_type_id'];


    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function parking_spot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }
}
