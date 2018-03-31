<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $fillable = [
		'user_id', 
		'entry_name', 
		'phone_number', 
		'address'
	];
	
   	public $timestamps = false;
}
