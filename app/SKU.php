<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class SKU extends Model
{
    use Searchable;
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'code',
        'price',
        'stock',
        'fullname'
    ];

    protected $table = 'SKU';

    public function getImages()
    {
      return $this->hasMany(
          'App\SKUImages'
      );
    }


}
