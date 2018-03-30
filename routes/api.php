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

//Route::post('/test','testController@test');
Route::post('/search', 'SearchController@search');

Route::group([
	'prefix' => 'item'
], function($router){
	Route::get('', 'ItemController@index');
	Route::get('top10','ItemController@top10');
});

Route::get('/menu', 'navController@loadMenu');

//Route::get("/test/getproducttags", 'testController@getProductTags');

//Route::get("/test/skus", 'testController@sku');

Route::group([
    'prefix' =>'user'
], function($router){
    Route::get('statusLastTrans','TransactionController@getLastStatus');
    Route::get('profile','UserController@getProfile');
});