<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['parking_spot_id', 'user_id', 'registration_number', 'color', 'user_vehicle_id', 'start', 'end', 'cost_price'];

    protected $dates =[
      'start','end'
    ];

    public function parking_spot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_vehicle()
    {
        return $this->belongsTo(UserVehicle::class);
    }
}
