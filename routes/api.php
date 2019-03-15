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

Route::group([ 'namespace' => 'API' ], function () {

	Route::group([ 'prefix' => 'auth'], function () {

		Route::post('login', 'AuthController@login');
	    Route::get('check', 'AuthController@checkAuth')->middleware('auth:api');
	  
	    Route::group(['middleware' => 'auth:api'], function() {
	        Route::get('logout', 'AuthController@logout');
	        Route::get('user', 'AuthController@user');
	    });

	});

	Route::post('profile', 'UsersController@updateProfile')->middleware('auth:api');

	Route::group([ 'prefix' => 'users', 'middleware' => ['auth:api'] ], function(){

		Route::get('/{id}', 'UsersController@getUser');

	});
    
});

