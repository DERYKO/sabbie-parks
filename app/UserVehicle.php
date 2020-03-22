<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVehicle extends Model
{
    protected $fillable = ['user_id', 'vehicle_type_id', 'registration_no', 'model_type', 'color'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle_type(){
        return $this->belongsTo(VehicleType::class);
    }
}
