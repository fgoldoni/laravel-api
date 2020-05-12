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

Route::group(['middleware' => ['jwt.verify', 'http.logger'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('transactions/{id}', 'TransactionsController@show')->name('transactions.show');
    Route::get('transactions/invoice/{id}', 'TransactionsController@invoice')->name('transactions.invoice');
    Route::post('transactions/paypal', 'TransactionsController@paypal')->name('transactions.paypal');
});
