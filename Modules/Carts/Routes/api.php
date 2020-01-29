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

Route::group(['middleware' => ['jwt.verify'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('orders', 'OrdersController@getOrders')->name('orders');
    Route::get('orders/paginate', 'OrdersController@paginate')->name('orders.paginate');
    Route::get('orders/{id}/edit', 'OrdersController@edit')->name('orders.edit')->where('id', '[0-9]+');
    Route::get('orders/create', 'OrdersController@create')->name('orders.create');
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    Route::put('orders/{event}', 'OrdersController@update')->name('orders.update');
    Route::delete('orders/{id}', 'OrdersController@destroy')->name('orders.destroy');
    Route::delete('orders/{id}/destroy', 'OrdersController@forceDelete')
        ->name('admin.orders.forceDelete')->where('id', '[0-9]+');
    Route::put('orders/{id}/restore', 'OrdersController@restore')->name('admin.orders.restore')->where('id', '[0-9]+');
});
