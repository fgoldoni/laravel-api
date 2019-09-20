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

Route::group(['middleware' => ['auth:api', 'role:Admin'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('users', 'UsersController@getUsers')->name('users');
    Route::get('users/paginate', 'UsersController@paginate')->name('users.paginate');
    Route::get('users/{user}', 'UsersController@show')->name('users.show');
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');
    Route::delete('users/{user}', 'UsersController@destroy')->name('users.destroy');
    Route::delete('users/{id}/destroy', 'UsersController@forceDelete')
        ->name('admin.users.forceDelete')->where('id', '[0-9]+');
    Route::put('users/{id}/restore', 'UsersController@restore')->name('admin.users.restore')->where('id', '[0-9]+');
});
