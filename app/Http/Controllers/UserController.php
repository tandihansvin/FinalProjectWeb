<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getProfile(){
        try{
            $user = User::findOrFail(auth('api')->user())->first();
            $user->address;
            return $user;
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['msg' => 'Failed to retrieve data'], 401);
        }
    }

    public function updateProfile(Request $request){
        try{
            $user = auth('api')->user();
//            return $user;
            $validator = Validator::make($request->all(),[
                'email' => 'required|email|unique:users,email,'.$user->id,
                'name' => 'required',
                'phone' => 'required|numeric',
            ]);
            if($validator->fails()) return response()->json($validator->errors(),401);

            $user = User::findOrFail($user->id)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            return response()->json(['msg' => 'Success'],200);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['msg' => 'Failed'], 401);
        }
    }
}
