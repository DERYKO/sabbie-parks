<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'transaction_type', 'debit', 'credit', 'balance'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
