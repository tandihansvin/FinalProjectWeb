<?php

namespace App\Http\Controllers;

use App\TransactionHeader;
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
}
