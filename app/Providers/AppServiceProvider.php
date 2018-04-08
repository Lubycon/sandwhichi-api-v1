<?php

namespace App\Providers;

use App\Models\PasswordReset;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
     public function boot()
     {
         $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
         if ($this->app->environment() !== 'production') {
             $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
         }
     }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
