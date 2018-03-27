<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Item extends Model
{
    use Uuids;
    protected $table = 'item';
    protected $fillable = ['name','price','desc'];
    public $incrementing = false;
}
