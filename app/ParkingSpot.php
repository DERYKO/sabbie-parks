<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    protected $fillable = ['parking_spot_code', 'client_id', 'status', 'latitude', 'longitude', 'land_mark'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function allowed()
    {
        return $this->hasMany(Allowed::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }
    public function pricing(){
        return $this->hasOne(Pricing::class);
    }
}
