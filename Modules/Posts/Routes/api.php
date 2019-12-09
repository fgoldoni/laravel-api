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
    Route::get('posts', 'PostsController@getPosts')->name('posts');
    Route::get('posts/paginate', 'PostsController@paginate')->name('posts.paginate');
    Route::get('posts/{id}/edit', 'PostsController@edit')->name('posts.edit')->where('id', '[0-9]+');
    Route::get('posts/create', 'PostsController@create')->name('posts.create');
    Route::post('posts', 'PostsController@store')->name('posts.store');
    Route::put('posts/{user}', 'PostsController@update')->name('posts.update');
    Route::delete('posts/{user}', 'PostsController@destroy')->name('posts.destroy');
    Route::delete('posts/{id}/destroy', 'PostsController@forceDelete')
        ->name('admin.posts.forceDelete')->where('id', '[0-9]+');
    Route::put('posts/{id}/restore', 'PostsController@restore')->name('admin.posts.restore')->where('id', '[0-9]+');
});
