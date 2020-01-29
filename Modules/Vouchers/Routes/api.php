<?php

use App\Flag;

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

Route::group(['middleware' => ['jwt.verify', 'permission:' . Flag::PERMISSION_ADMIN], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('vouchers', 'VouchersController@getVouchers')->name('vouchers');
    Route::get('vouchers/paginate', 'VouchersController@paginate')->name('vouchers.paginate');
    Route::get('vouchers/{id}/edit', 'VouchersController@edit')->name('vouchers.edit')->where('id', '[0-9]+');
    Route::get('vouchers/create', 'VouchersController@create')->name('vouchers.create');
    Route::post('vouchers', 'VouchersController@store')->name('vouchers.store');
    Route::put('vouchers/{event}', 'VouchersController@update')->name('vouchers.update');
    Route::delete('vouchers/{user}', 'VouchersController@destroy')->name('vouchers.destroy');
    Route::delete('vouchers/{id}/destroy', 'VouchersController@forceDelete')
        ->name('admin.vouchers.forceDelete')->where('id', '[0-9]+');
    Route::put('vouchers/{id}/restore', 'VouchersController@restore')->name('admin.vouchers.restore')->where('id', '[0-9]+');
});
