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

Route::group(['middleware' => ['auth:api', 'role:' . Flag::ROLE_ADMIN], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('categories', 'CategoriesController@getCategories')->name('categories');
    Route::get('categories/paginate', 'CategoriesController@paginate')->name('categories.paginate');
    Route::get('categories/{id}/edit', 'CategoriesController@edit')->name('categories.edit')->where('id', '[0-9]+');
    Route::get('categories/create', 'CategoriesController@create')->name('categories.create');
    Route::post('categories', 'CategoriesController@store')->name('categories.store');
    Route::put('categories/{category}', 'CategoriesController@update')->name('categories.update');
    Route::delete('categories/{category}', 'CategoriesController@destroy')->name('categories.destroy');
    Route::get('categories/children/{category}', 'CategoriesController@children')->name('categories.children');
    Route::get('categories/append/{category}', 'CategoriesController@append')->name('categories.append');
});
