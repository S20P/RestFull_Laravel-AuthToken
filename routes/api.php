<?php

// use Illuminate\Http\Request;
//
// /*
// |--------------------------------------------------------------------------
// | API Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register API routes for your application. These
// | routes are loaded by the RouteServiceProvider within a group which
// | is assigned the "api" middleware group. Enjoy building your API!
// |
// */
//
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'prefix' => 'auth'

], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');

});

 //---------------------------------------------------------------------------------------------------

 // USER api

 //List User
 Route::get('user', 'UserApiController@index');

 //list Single User
 Route::get('user/{id}', 'UserApiController@show');

 //Create new User
 Route::post('user/create', 'UserApiController@create');

 //Update  User
 Route::post('user/update/{id}', 'UserApiController@update');

 //Delete User
 Route::delete('user/delete/{id}', 'UserApiController@destroy');
