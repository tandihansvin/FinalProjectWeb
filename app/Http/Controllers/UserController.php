<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllAddresses(){
        try{
            return User::findOrFail(auth('api')->user())->first()->getAddress;
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['msg' => 'Failed to retrieve data'], 401);
        }
    }
}
