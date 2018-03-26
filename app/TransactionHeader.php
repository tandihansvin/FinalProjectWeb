<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    protected $fillable = [
        'time',
        'user_id',
        'total'
    ];
}
