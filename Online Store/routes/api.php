<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


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

Route::get('/', function (Request $request) {
    return "Hai";
});

Route::fallback(function(){
    return (new ApiController)->sendResponse([], 'Page Not Found. If error persists, contact ranakrisna17031995@gmail.com', 404, false);
});

Route::group(['prefix'=>'v1'], function () {
	Route::get('stores', 'App\Http\Controllers\StoreController@index');
	Route::group(['prefix'=>'store'], function () {
		Route::post('', 'App\Http\Controllers\StoreController@store');
		Route::group(['prefix'=>'{store}'], function () {
			Route::get('', 'App\Http\Controllers\StoreController@show');
			Route::put('', 'App\Http\Controllers\StoreController@update');
			Route::delete('', 'App\Http\Controllers\StoreController@destroy');
			Route::get('/products', 'App\Http\Controllers\StoreController@showProducts');
		});
	});

	Route::get('products', 'App\Http\Controllers\ProductController@index');
	Route::group(['prefix'=>'product'], function () {
		Route::post('', 'App\Http\Controllers\ProductController@store');
		Route::group(['prefix'=>'{product}'], function () {
			Route::get('', 'App\Http\Controllers\ProductController@show');
			Route::put('', 'App\Http\Controllers\ProductController@update');
			Route::delete('', 'App\Http\Controllers\ProductController@destroy');
			Route::get('/flash-sale', 'App\Http\Controllers\ProductController@showFlashSale');
			Route::post('/flash-sale', 'App\Http\Controllers\ProductController@storeFlashSale');
		});
	});

	Route::get('orders', 'App\Http\Controllers\OrderController@index');
	Route::group(['prefix'=>'order'], function () {
		Route::post('', 'App\Http\Controllers\OrderController@store');
		Route::group(['prefix'=>'{order}'], function () {
			Route::get('', 'App\Http\Controllers\OrderController@show');
			Route::put('', 'App\Http\Controllers\OrderController@update');
			Route::delete('', 'App\Http\Controllers\OrderController@destroy');
		});
	});
});



