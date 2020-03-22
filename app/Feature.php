<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['parking_spot_id', 'security_id'];

    public function security()
    {
        return $this->belongsTo(Security::class);
    }

    public function parking_spot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }
}
