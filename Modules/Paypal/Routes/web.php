<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web', 'auth', 'verified'], 'prefix' => '/user'], function () {
    Route::get('paypal', 'PaypalController@index')->name('user.paypal');
    Route::get('paypal/buy', 'PaypalController@buy')->name('user.paypal.buy');
    Route::get('paypal/pay', 'PaypalController@pay')->name('user.paypal.pay');
});
