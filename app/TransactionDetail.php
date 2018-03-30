<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'header_id',
        'sku_id',
        'qty',
        'paid'
    ];
    public function sku(){
        return $this->belongsTo('App\SKU');
    }
}
