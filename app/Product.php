<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'short_desc', 'long_desc'];

    public function getSKUs()
    {
        return $this->hasMany(
            'App\SKU'
        );
    }

    public function getTags()
    {
        return $this->hasManyThrough(
            'App\Tag',
            'App\ProductTag'
        );
    }
}
