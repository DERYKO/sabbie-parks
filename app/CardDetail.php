<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardDetail extends Model
{
    protected $fillable = ['user_id', 'card_type', 'card_number', 'holders_name', 'cvs_number', 'expiry_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
