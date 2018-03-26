<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $fillable = [
		'user_id', 
		'entry_name', 
		'phone_number', 
		'address'
	];
	
   	public $timestamps = false;
}
