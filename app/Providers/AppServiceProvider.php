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

         Validator::extend('base64Image', function ($attribute, $value, $params, $validator) {
             $explodeBase64 = explode('data:image/',$value);
             return count($explodeBase64) > 1;
         });
         Validator::extend('availableEmail', function ($attribute, $value, $params, $validator) {
             return User::isAvailableEmail($value);
         });

         Validator::extend('availableNickname', function ($attribute, $value, $params, $validator) {
             return User::isAvailableNickname($value);
         });

         Validator::extend('availablePassword', function ($attribute, $value, $params, $validator) {
             return PasswordReset::isAvailable($value);
         });

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
