<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id','user_vehicle_id', 'parking_spot_id', 'expiry_time', 'registration_number', 'color', 'inconvenience_fee'];

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
    public function scopeFilterBy($q,$filters){
        if (!isset($filters['date'])){
            $filters['date'] = Carbon::now()->toDateString();
        }
        $q->whereBetween('created_at',[Carbon::parse($filters['date'])->startOfDay(),Carbon::parse($filters['date'])->endOfDay()]);
    }
}
