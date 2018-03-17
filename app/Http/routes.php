<?php

if (App::environment('local')) {
    // The environment is local
    DB::enableQueryLog();
    if (env('DB_LOG', false)) {
        DB::listen(function ($query) {
            Log::info( print_r([
                'sql'      => $query->sql,
                'bindings' => $query->bindings,
                'time'     => $query->time,
            ],true));
        });
    }
}

Route::get('/', function () {
    return view('welcome', ['ifYouWantPassData' => 'put in here', 'and' => 'more']);
});

Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/users/'], function () {
        Route::post('signup', 'Service\Auth\AuthController@signup');
        Route::post('signin', 'Service\Auth\AuthController@signin');
        Route::put('signout', 'Service\Auth\AuthController@signout');
        Route::delete('signdrop', 'Service\Auth\AuthController@signdrop');
        Route::get('token/refresh', 'Service\Auth\AuthController@refreshAccessToken');

        Route::get('me', 'Service\User\UserController@profile');

        Route::group(['prefix' => 'exists/'], function () {
            Route::post('email', 'Service\Auth\AuthController@emailExist');
        });

        Route::group(['prefix' => '/google/'], function () {
            Route::post('signin', 'Service\Auth\AuthController@googleSignin');
            Route::post('signup', 'Service\Auth\AuthController@googleSignup');
            Route::get('profile/id-token/{id_token}', 'Service\User\UserController@googleProfile');
        });

        Route::group(['prefix' => '/naver/'], function () {
            Route::post('signin', 'Service\Auth\AuthController@naverSignin');
            Route::post('signup', 'Service\Auth\AuthController@naverSignup');
            Route::get('profile/code/{code}/state/{state}', 'Service\User\UserController@naverProfile');
        });
    });
});

Route::get('/ping', function () {
    return 'pong';
});