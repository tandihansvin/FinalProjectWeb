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

Route::post('/filter', 'ProductController@search');
Route::get('/product','ProductController@getSKU');
Route::get('/product/topProduct', 'ProductController@getTopProduct');
Route::post('/cart','TransactionController@addToCart');

//Route::group([

//	'prefix' => 'item'
//], function($router){
//	Route::get('', 'ItemController@index');
//	Route::get('top10','ItemController@top10');
//});

Route::get('/menu', 'navController@loadMenu');

//Route::get("/test/getproducttags", 'testController@getProductTags');

//Route::get("/test/skus", 'testController@sku');

Route::post('test/midtrans', 'PaymentController@createTransaction');
Route::post('transaction/create', 'PaymentController@createTransaction');
Route::post('transaction/callback', 'PaymentController@callback');

Route::get('/checkExpire','TransactionController@checkExpired');

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
});