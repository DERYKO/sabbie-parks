<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'logo', 'country_code', 'country', 'region_id', 'location_id', 'address', 'telephone_no', 'telephone_no_1', 'email_address', 'website', 'postal_address', 'postal_code', 'latitude', 'longitude'];

    public function spots()
    {
        return $this->hasMany(ParkingSpot::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
