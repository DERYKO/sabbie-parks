<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubLocality extends Model
{
    protected $fillable = ['parking_spot_id', 'formatted_address'];
}
