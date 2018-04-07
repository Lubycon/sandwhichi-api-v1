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
        Route::post('signin', 'Service\Auth\AuthController@signin');
        Route::put('signout', 'Service\Auth\AuthController@signout');
        Route::post('signup', 'Service\Auth\AuthController@signup');
        Route::delete('signdrop', 'Service\Auth\AuthController@signdrop');
        Route::get('token/refresh', 'Service\Auth\AuthController@refreshAccessToken');

        Route::group(['prefix' => 'exists/'], function () {
            Route::post('email', 'Service\Auth\AuthController@emailExist');
        });
    });

    Route::group(['prefix' => 'projects/'], function () {
        Route::post('', 'Service\Project\ProjectController@create');
    });


    Route::group(['prefix' => '/keywords/'], function () {
        Route::post('{name}', 'Service\Keyword\KeywordController@search');
    });

    Route::group(['prefix' => '/abilities/'], function () {
        Route::post('{name}', 'Service\Ability\AbilityController@search');
    });
});

Route::get('/ping', function () {
    return 'pong';
});