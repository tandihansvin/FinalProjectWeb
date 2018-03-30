<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\User;
use App\Address;
use Validator;
use Illuminate\Support\Facades\Hash;

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

            $validator = Validator::make($request->all(),[
                'email' => 'required|email|unique:users,email,'.$user->id,
                'name' => 'required',
                'phone' => 'required|numeric',
            ]);
            if($validator->fails()) return response()->json($validator->errors(),401);

            $user = User::findOrFail($user->id);
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

    public function updatePassword(Request $request){
        try{
            $user = auth('api')->user();
//            return $user;
            $validator = Validator::make($request->all(),[
                'pass' => 'required',
                'repass' => 'required'
            ]);
            if($validator->fails()) return response()->json($validator->errors(),401);
            if($request->pass != $request->repass) return response()->json(['msg'=>'invalid'],401);

            $user = User::findOrFail($user->id);
            $user->password = Hash::make($request->pass);
            $user->save();

            return response()->json(['msg' => 'Success'],200);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['msg' => 'Failed'], 401);
        }
    }

    public function deleteAddress(Request $request){
        try{
            $address = Address::findorFail($request->id);
            if($address->user_id != auth('api')->user()->id) return response()->json(['msg'=>'unauthorize'],401);
            else {
                $address->delete();
                return response()->json(['msg'=>'success'],200);
            }
        }
        catch(ModelNotFoundException $e){
            return response()->json(['msg'=>'not found address'],401);
        }
    }
}
