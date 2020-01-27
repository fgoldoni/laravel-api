<?php

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

Route::group(['middleware' => ['jwt.verify'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('paypal', 'PaypalController@index')->name('paypal');
    Route::get('paypal/purchase', 'PaypalController@purchase')->name('paypal.purchase');
    Route::get('paypal/charge', 'PaypalController@charge')->name('paypal.charge');
});
