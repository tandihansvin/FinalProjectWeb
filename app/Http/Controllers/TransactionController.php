<?php

namespace App\Http\Controllers;

use App\TransactionHeader;
use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getLastStatus(){
//        return auth('api')->user()->id;
        $headTrans = TransactionHeader::where('user_id',auth('api')->user()->id)->get();
        foreach ($headTrans as &$trans){
            $trans->address;
            $trans['status'] = $trans->statusChange()->latest('time')->first()->status->name;
        }
        return $headTrans;
    }

    public function getDetail(Request $request){
        try{
            $details = TransactionHeader::findOrFail($request->id)->transactionDetail;
            foreach($details as $detail){
                $detail->sku->color;
                $detail->sku->size;
            }
            return $details;
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['msg' => 'Failed'], 401);
        }
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
}
