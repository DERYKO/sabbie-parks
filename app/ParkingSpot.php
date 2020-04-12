<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    protected $fillable = ['parking_spot_code', 'location_id', 'client_id', 'status', 'latitude', 'longitude', 'land_mark'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function allowed()
    {
        return $this->hasMany(Allowed::class);
    }

    public function pricing()
    {
        return $this->hasOne(Pricing::class, 'parking_spot_id', 'id');
    }

    public function level3()
    {
        return $this->belongsTo(AdministrativeAreaLevel3::class,'id','parking_spot_id');
    }

    public function level4()
    {
        return $this->belongsTo(AdministrativeAreaLevel4::class,'id','parking_spot_id');
    }
}
