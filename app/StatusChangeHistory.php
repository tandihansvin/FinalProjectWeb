<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusChangeHistory extends Model
{
    protected $fillable = [
        'time',
        'header_id',
        'status_id',
        'desc'
    ];

    public function status(){
        return $this->belongsTo('App\Status', 'status_id');
    }
}
