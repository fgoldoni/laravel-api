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
    Route::get('stripe', 'StripeController@getStripe')->name('stripe');
    Route::get('stripe/intent', 'StripeController@intent')->name('stripe.intent');
    Route::get('stripe/paginate', 'StripeController@paginate')->name('stripe.paginate');
    Route::get('stripe/{id}/edit', 'StripeController@edit')->name('stripe.edit')->where('id', '[0-9]+');
    Route::get('stripe/create', 'StripeController@create')->name('stripe.create');
    Route::post('stripe', 'StripeController@store')->name('stripe.store');
    Route::put('stripe/{event}', 'StripeController@update')->name('stripe.update');
    Route::delete('stripe/{id}', 'StripeController@destroy')->name('stripe.destroy');
    Route::delete('stripe/{id}/destroy', 'StripeController@forceDelete')
        ->name('admin.stripe.forceDelete')->where('id', '[0-9]+');
    Route::put('stripe/{id}/restore', 'StripeController@restore')->name('admin.stripe.restore')->where('id', '[0-9]+');
});
