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
        return $this->hasMany('App\SKU');
    }

    public function tags()
    {
        return $this->hasMany('App\ProductTag', 'product_id');
    }

    public function toSearchableArray()
    {
        $x = $this->toArray();

        $x['tags'] = $this->tags;
        foreach($x['tags'] as &$tag){
            $tag->tag;
        }

        $x['sku'] = $this->skus;
        foreach($x['sku'] as &$sku){
            $sku->color;
            $sku->size;
            $sku->image;
        }

        return $x;
    }
}
