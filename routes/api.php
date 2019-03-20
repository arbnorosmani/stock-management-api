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

Route::group([ 'namespace' => 'API' ], function () {

	// Auth routes
	Route::group([ 'prefix' => 'auth'], function () {

		Route::post('login', 'AuthController@login');
	    Route::get('check', 'AuthController@checkAuth')->middleware('auth:api');
	  
	    Route::group(['middleware' => 'auth:api'], function() {
	        Route::get('logout', 'AuthController@logout');
	        Route::get('user', 'AuthController@user');
	    });

	});

	Route::post('profile', 'UsersController@updateProfile')->middleware('auth:api');

	// User routes
	Route::group([ 'prefix' => 'users', 'middleware' => ['auth:api'] ], function(){

		Route::get('/', 'UsersController@getUsers');
		Route::delete('/delete/{id}', 'UsersController@destroy');
		Route::post('/delete/bulk', 'UsersController@bulkDelete');
		Route::post('/store', 'UsersController@store');
		Route::post('/update', 'UsersController@update');
		Route::get('/{id}', 'UsersController@getUser');

	});

	// Settings routes
	Route::group([ 'prefix' => 'settings', 'middleware' => ['auth:api'] ], function(){

		Route::get('/', 'SettingsController@getSettings');
		Route::post('/update', 'SettingsController@updateSettings');

	});

	// Category routes
	Route::group([ 'prefix' => 'categories', 'middleware' => ['auth:api'] ], function(){

		Route::get('/', 'CategoriesController@getCategories');
		Route::delete('/delete/{id}', 'CategoriesController@destroy');
		Route::post('/delete/bulk', 'CategoriesController@bulkDelete');
		Route::post('/store', 'CategoriesController@store');
		Route::post('/update', 'CategoriesController@update');
		Route::get('/{id}', 'CategoriesController@getCategory');

		Route::post('/generate/slug', 'CategoriesController@generateSlug');
		Route::get('/search/{value}', 'CategoriesController@search');

	});

	// Brand routes
	Route::group([ 'prefix' => 'brands', 'middleware' => ['auth:api'] ], function(){

		Route::get('/', 'BrandsController@getBrands');
		Route::delete('/delete/{id}', 'BrandsController@destroy');
		Route::post('/delete/bulk', 'BrandsController@bulkDelete');
		Route::post('/store', 'BrandsController@store');
		Route::post('/update', 'BrandsController@update');
		Route::get('/{id}', 'BrandsController@getBrand');

		Route::post('/generate/slug', 'BrandsController@generateSlug');
		Route::get('/search/{value}', 'BrandsController@search');

	});


	// Product routes
	Route::group([ 'prefix' => 'products', 'middleware' => ['auth:api'] ], function(){

		Route::get('/', 'ProductsController@getProducts');
		Route::delete('/delete/{id}', 'ProductsController@destroy');
		Route::post('/delete/bulk', 'ProductsController@bulkDelete');
		Route::post('/store', 'ProductsController@store');
		Route::post('/update', 'ProductsController@update');
		Route::get('/{id}', 'ProductsController@getProduct');

		Route::post('/generate/slug', 'ProductsController@generateSlug');

	});
    
});

