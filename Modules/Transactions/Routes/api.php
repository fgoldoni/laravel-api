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
    Route::get('transactions/invoice/{id}', 'TransactionsController@invoice')->name('transactions.invoice');
});
