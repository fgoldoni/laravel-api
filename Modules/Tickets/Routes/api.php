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
    Route::get('tickets', 'TicketsController@getTickets')->name('tickets');
    Route::get('tickets/paginate', 'TicketsController@paginate')->name('tickets.paginate');
    Route::get('tickets/{id}/edit', 'TicketsController@edit')->name('tickets.edit')->where('id', '[0-9]+');
    Route::get('tickets/create/{id}', 'TicketsController@create')->name('tickets.create')->where('id', '[0-9]+');
    Route::post('tickets', 'TicketsController@store')->name('tickets.store');
    Route::post('tickets/duplicate/{ticket}', 'TicketsController@duplicate')->name('tickets.duplicate');
    Route::put('tickets/{event}', 'TicketsController@update')->name('tickets.update');
    Route::delete('tickets/{ticket}', 'TicketsController@destroy')->name('tickets.destroy');
    Route::delete('tickets/{id}/destroy', 'TicketsController@forceDelete')
        ->name('admin.tickets.forceDelete')->where('id', '[0-9]+');
    Route::put('tickets/{id}/restore', 'TicketsController@restore')->name('admin.tickets.restore')->where('id', '[0-9]+');
});
