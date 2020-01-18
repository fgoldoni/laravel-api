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

use App\Flag;

Route::group(['middleware' => ['jwt.verify', 'role:' . Flag::ROLE_ADMIN], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('users', 'UsersController@getUsers')->name('users');
    Route::get('users/paginate', 'UsersController@paginate')->name('users.paginate');
    Route::get('users/{id}/edit', 'UsersController@edit')->name('users.edit')->where('id', '[0-9]+');
    Route::get('users/create', 'UsersController@create')->name('users.create');
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');
    Route::delete('users/{user}', 'UsersController@destroy')->name('users.destroy');
    Route::delete('users/{id}/destroy', 'UsersController@forceDelete')
        ->name('admin.users.forceDelete')->where('id', '[0-9]+');
    Route::put('users/{id}/restore', 'UsersController@restore')->name('admin.users.restore')->where('id', '[0-9]+');
});

Route::group(['middleware' => [], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::post('auth/login', 'AuthController@login')->name('auth.login');
    Route::post('auth/link', 'AuthController@link')->name('auth.link');
    Route::post('auth/token', 'AuthController@token')->name('auth.token');
    Route::get('auth/magiclink/{token}', 'AuthController@magiclink')->name('auth.magiclink');
    Route::post('auth/register', 'AuthController@register')->name('auth.register');
    Route::post('auth/refresh', 'AuthController@refresh')->name('auth.refresh');
});

Route::group(['middleware' => ['jwt.verify'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::post('auth/refresh', 'AuthController@refresh')->name('auth.refresh');
    Route::get('users/tickets', 'UsersController@tickets')->name('users.tickets');
});

Route::group(['middleware' => ['jwt.verify'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::post('auth/user', 'AuthController@user')->name('auth.user');
});
