<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['parking_spot_id', 'security_id'];

    public function security()
    {
        return $this->belongsTo(Security::class,'security_id','id');
    }

    public function parking_spot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }
}
