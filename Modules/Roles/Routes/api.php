<?php

use Illuminate\Http\Request;

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
    Route::get('roles', 'RolesController@getRoles')->name('roles');
    Route::get('roles/paginate', 'RolesController@paginate')->name('roles.paginate');
    Route::get('roles/{id}/edit', 'RolesController@edit')->name('roles.edit')->where('id', '[0-9]+');
    Route::get('roles/create', 'RolesController@create')->name('roles.create');
    Route::post('roles', 'RolesController@store')->name('roles.store');
    Route::put('roles/{user}', 'RolesController@update')->name('roles.update');
    Route::delete('roles/{user}', 'RolesController@destroy')->name('roles.destroy');
    Route::delete('roles/{id}/destroy', 'RolesController@forceDelete')
        ->name('admin.roles.forceDelete')->where('id', '[0-9]+');
    Route::put('roles/{id}/restore', 'RolesController@restore')->name('admin.roles.restore')->where('id', '[0-9]+');
});
