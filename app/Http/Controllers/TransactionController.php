<?php

namespace App\Http\Controllers;

use App\TransactionHeader;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getLastStatus(){
//        return auth('api')->user()->id;
        $headTrans = TransactionHeader::where('user_id',auth('api')->user()->id)->get();
        foreach ($headTrans as $trans){
            $trans->statusChange->latest('time')->first()->status;
        }
        return $headTrans;
    }
}
