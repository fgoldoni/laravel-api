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

Route::group(['middleware' => ['jwt.verify', 'role:' . Flag::ROLE_ADMIN, 'http.logger'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('dashboard', 'DashboardController@getDashboard')->name('dashboard');
    Route::get('dashboard/paginate', 'DashboardController@paginate')->name('dashboard.paginate');
    Route::get('dashboard/{id}/edit', 'DashboardController@edit')->name('dashboard.edit')->where('id', '[0-9]+');
    Route::get('dashboard/create', 'DashboardController@create')->name('dashboard.create');
    Route::post('dashboard', 'DashboardController@store')->name('dashboard.store');
    Route::put('dashboard/{user}', 'DashboardController@update')->name('dashboard.update');
    Route::delete('dashboard/{user}', 'DashboardController@destroy')->name('dashboard.destroy');
    Route::delete('dashboard/{id}/destroy', 'DashboardController@forceDelete')
        ->name('admin.dashboard.forceDelete')->where('id', '[0-9]+');
    Route::put('dashboard/{id}/restore', 'DashboardController@restore')->name('admin.dashboard.restore')->where('id', '[0-9]+');
});
