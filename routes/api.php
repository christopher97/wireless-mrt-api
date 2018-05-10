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

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'UserController@logout');

    // lane
    Route::get('lane', 'LaneController@fetch');
    Route::get('lane/{id}', 'LaneController@find');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
