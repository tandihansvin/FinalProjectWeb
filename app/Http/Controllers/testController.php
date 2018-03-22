<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;


class testController extends Controller
{
    function test(Request $request){
    	return User::create([
    		'name'=>$request->name,
    		'email'=>$request->email,
    		'password'=>Hash::make($request->password)
    	]);
    }
}
