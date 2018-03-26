<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'SKU_id', 'qty'];
   	public $timestamps = false;

   	public function getSKU()
    {
   	    return $this->belongsTo('App\SKU');
    }
}
