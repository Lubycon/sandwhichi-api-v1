<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\Authenticate::class,
        \App\Http\Middleware\CorsHandler::class,
        \App\Http\Middleware\UserInformationCapture::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.admin' => \App\Http\Middleware\AdminAuthenticate::class,
    ];
}
