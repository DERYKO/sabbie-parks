<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    protected $fillable = ['parking_spot_id', 'formatted_address'];
}
