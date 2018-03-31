<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    protected $fillable = [
        'time',
        'user_id',
        'address_id',
        'total'
    ];

    public function transactionDetail(){
        return $this->hasMany('App\TransactionDetail','header_id');
    }

    public function statusChange(){
        return $this->hasMany('App\StatusChangeHistory','header_id');
    }

    public function address(){
        return $this->belongsTo('App\Address');
    }
}
