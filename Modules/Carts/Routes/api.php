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
    Route::get('carts', 'CartsController@getCarts')->name('carts');
    Route::post('carts/coupon', 'CartsController@coupon')->name('carts.coupon');
    Route::get('carts/paginate', 'CartsController@paginate')->name('carts.paginate');
    Route::get('carts/{id}/edit', 'CartsController@edit')->name('carts.edit')->where('id', '[0-9]+');
    Route::get('carts/create', 'CartsController@create')->name('carts.create');
    Route::post('carts', 'CartsController@store')->name('carts.store');
    Route::put('carts/{event}', 'CartsController@update')->name('carts.update');
    Route::delete('carts/{id}', 'CartsController@destroy')->name('carts.destroy');
    Route::delete('carts/{id}/destroy', 'CartsController@forceDelete')
        ->name('admin.carts.forceDelete')->where('id', '[0-9]+');
    Route::put('carts/{id}/restore', 'CartsController@restore')->name('admin.carts.restore')->where('id', '[0-9]+');
});
