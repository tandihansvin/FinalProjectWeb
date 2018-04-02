<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addToCart(Request $request){
        $x = Cart::where('user_id',auth('api')->user()->id)
            ->where('SKU_id',$request->id)->first();
        //ada duplicate
        if($x){
            $x->qty = $x->qty+1;
            $x->save();
        }
        //ngga ada duplicate
        else{
            Cart::create([
                'user_id'=>auth('api')->user()->id,
                'SKU_id'=>$request->id,
                'qty'=>1
            ]);
        }
        return response()->json(['msg'=>'success'],200);
    }

    public function loadCart(){
        $carts = Cart::where('user_id',auth('api')->user()->id)->get();
        foreach($carts as $cart){
            $cart->sku->color;
            $cart->sku->size;
            $cart->sku->images;
        }
        return $carts;
    }

    public function updateCart(Request $request){
        $carts = $request;
        foreach ($carts as $cart){
            $tmp = Cart::find($cart['id']);
            $tmp->qty = $cart['qty'];
            $tmp->save();
        }
    }
}
