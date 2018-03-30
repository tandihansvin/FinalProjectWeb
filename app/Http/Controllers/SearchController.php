<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SKU;
use App\Product;
use App\ProductTag;

class SearchController extends Controller
{
    public function search(Request $request){
        $string = $request->q;

        if ($request->t){
            $tags = explode('+',$request->t);
        } else {
            $tags = false;
        }

        // Get products
        if ($tags and $string){
            $prids = ProductTag::whereIn("tag_id", $tags)
                ->groupBy('product_id')
                ->havingRaw('COUNT(1) = '.sizeof($tags))
                ->pluck('product_id');

            $products = Product::whereIn('id', $prids)
                ->where('name', 'like', "%$string%")
                ->get();
        } elseif (!$tags and $string) {
            $products = Product::where('name', 'like', "%$string%")
                ->paginate(16);
        } elseif ($tags and !$string){
            $prids = ProductTag::whereIn("tag_id", $tags)
                ->groupBy('product_id')
                ->havingRaw('COUNT(1) = '.sizeof($tags))
                ->pluck('product_id');
            $products = Product::whereIn('id', $prids)
                ->get();
        } else {
            return [];
        }

        // Get SKU
        foreach ($products as $product){
            $product->skus;
            foreach ($product->skus as $sku){
                $sku->color;
                $sku->size;
                $sku->images;
            }
        }
        return $products;
    }
}
