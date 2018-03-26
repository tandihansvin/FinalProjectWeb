<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SKUImages extends Model
{
    protected $fillable = [
        'sku_id',
        'small',
        'medium',
        'large'
    ];

    protected $table = "SKUImages";
}
