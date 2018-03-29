<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    protected $fillable = ['name', 'short_desc', 'long_desc'];

    public function skus()
    {
        return $this->hasMany(
            'App\SKU'
        );
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'product_tags');
    }

    public function toSearchableArray()
    {
        $array = $this->only('name','short_desct','long_desc');
        return $array;
    }
}
