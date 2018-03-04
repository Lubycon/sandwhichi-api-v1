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
        Route::group(['prefix' => '/signin/'], function () {
            Route::post('', 'Service\Auth\AuthController@signin');
            Route::post('google', 'Service\Auth\AuthController@googleSignin');
            Route::post('naver', 'Service\Auth\AuthController@naverSignin');
        });

        Route::put('signout', 'Service\Auth\AuthController@signout');

        Route::group(['prefix' => '/signup/'], function () {
            Route::post('', 'Service\Auth\AuthController@signup');
            Route::post('google', 'Service\Auth\AuthController@googleSignup');
            Route::post('naver', 'Service\Auth\AuthController@naverSignup');
        });

        Route::delete('signdrop', 'Service\Auth\AuthController@signdrop');
        Route::get('token/refresh', 'Service\Auth\AuthController@refreshAccessToken');

        Route::group(['prefix' => 'exists/'], function () {
            Route::post('email', 'Service\Auth\AuthController@emailExist');
        });
    });
});

Route::get('/ping', function () {
    return 'pong';
});