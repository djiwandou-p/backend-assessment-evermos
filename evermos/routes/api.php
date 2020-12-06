<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContainerController;


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


Route::group(['prefix'=>'player'], function () {
	Route::get('', 'App\Http\Controllers\PlayerController@index');
	Route::post('', 'App\Http\Controllers\PlayerController@store');

	Route::group(['prefix'=>'{player}'], function () {
		Route::get('', 'App\Http\Controllers\PlayerController@show');
		Route::put('', 'App\Http\Controllers\PlayerController@update');
		Route::delete('', 'App\Http\Controllers\PlayerController@delete');
		Route::group(['prefix'=>'container'], function () {
			Route::get('', 'App\Http\Controllers\ContainerController@index');
			Route::get('/{container}', 'App\Http\Controllers\ContainerController@show');
			Route::post('', 'App\Http\Controllers\ContainerController@store');
			Route::put('/{container}', 'App\Http\Controllers\ContainerController@update');
			Route::patch('/{container}', 'App\Http\Controllers\ContainerController@updateAmmount');
			Route::delete('/{container}', 'App\Http\Controllers\ContainerController@delete');
		});
	});
});



