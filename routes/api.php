<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'API', 'prefix' => 'v1'], function() {
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/register', 'Auth\RegisterController@register');
    Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth:api');
  
    Route::group(['middleware' => 'auth:api'], function() {

        // Book
        Route::group(['middleware' => 'can:Admin', 'prefix' => 'book'], function($route) {
            $route->get('', 'BookController@index');
            $route->post('/store', 'BookController@store');
            $route->post('/update', 'BookController@update');
            $route->post('/delete', 'BookController@delete');
        });

        // Rent
        Route::group(['middleware' => 'can:Staff', 'prefix' => 'rent'], function($route) {
            $route->get('', 'PaymentController@index');
            $route->post('store', 'PaymentController@store');
        });

    });
});