<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register','AuthController@register');

});

// Category Filtering
Route::post('/filter', 'ProductController@search');

// Product Page
Route::get('/product','ProductController@getSKU');
Route::get('/product/topProduct', 'ProductController@getTopProduct');

// Cart
Route::post('/cart','CartController@addToCart');
Route::get('/cart','CartController@loadCart');
Route::put('/cart','CartController@updateCart');

// Navigation
Route::get('/menu', 'navController@loadMenu');

// Transaction
Route::post('transaction/create', 'PaymentController@createTransaction');
Route::post('transaction/callback', 'PaymentController@callback');
Route::get('/checkExpire','TransactionController@checkExpired');

// User
Route::group([
    'prefix' =>'user'
], function($router){
    Route::get('statusLastTrans','TransactionController@getLastStatus');
    Route::get('detailHead','TransactionController@getDetail');

    Route::get('profile','UserController@getProfile');
    Route::put('updateProfile','UserController@updateProfile');
    Route::put('updatePassword', 'UserController@updatePassword');
    Route::delete('deleteAddress','UserController@deleteAddress');
    Route::post('addAddress','UserController@addAddress');
    Route::get('address', 'UserController@getAddress');
});