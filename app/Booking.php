<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'parking_spot_id', 'expiry_time', 'registration_number', 'color', 'inconvenience_fee', 'scheduled_book_time'];

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
