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

Route::group(['middleware' => ['auth:api', 'role:' . Flag::ROLE_ADMIN], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::get('attachments', 'AttachmentsController@getAttachments')->name('users');
});

Route::group(['middleware' => ['api'], 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::post('attachments/store', 'AttachmentsController@store')->name('attachments.store');
    Route::delete('attachments/{attachment}', 'AttachmentsController@destroy')->name('attachments.destroy');
});
