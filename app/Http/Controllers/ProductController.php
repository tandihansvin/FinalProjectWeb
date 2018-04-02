<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
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

    public function getSKU(Request $request){
//            return Product::all();
        try{
            $product = Product::findOrFail($request->id);
//            dd($request->id);
            $product->skus;
            foreach ($product->skus as $sku){
                $sku->color;
                $sku->size;
                $sku->images;
            }
            return $product;
        }
        catch(ModelNotFoundException $e){
            return response()->json(['msg'=>'product is not found'],401);
        }
    }

    public function getTopProduct(){
        $products = Product::orderBy('id','desc')->take(4)->get();
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
