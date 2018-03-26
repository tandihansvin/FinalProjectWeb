<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'parent_id',
        'name'
    ];
    public $timestamps = false;
}
