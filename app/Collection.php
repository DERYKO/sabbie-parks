<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected  $fillable = ['client_id','parking_spot_id','payment_type','merchantRequestId','checkoutRequestId','amount','balance','partyA','partyB','status','receipt_no'];

}
