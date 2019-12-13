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

Route::group(['middleware' => ['auth:api', 'permission:' . Flag::PERMISSION_EVENT_MANAGER], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('events', 'EventsController@getEvents')->name('events');
    Route::get('events/paginate', 'EventsController@paginate')->name('events.paginate');
    Route::get('events/{id}/edit', 'EventsController@edit')->name('events.edit')->where('id', '[0-9]+');
    Route::get('events/create', 'EventsController@create')->name('events.create');
    Route::post('events', 'EventsController@store')->name('events.store');
    Route::put('events/{user}', 'EventsController@update')->name('events.update');
    Route::delete('events/{user}', 'EventsController@destroy')->name('events.destroy');
    Route::delete('events/{id}/destroy', 'EventsController@forceDelete')
        ->name('admin.events.forceDelete')->where('id', '[0-9]+');
    Route::put('events/{id}/restore', 'EventsController@restore')->name('admin.events.restore')->where('id', '[0-9]+');
});
