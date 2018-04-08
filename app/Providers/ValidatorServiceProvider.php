<?php

namespace App\Providers;

use App\Models\Media;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use Request;
use Log;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot(ResponseFactory $factory)
    {
        Validator::extend('base64Image', function ($attribute, $value, $params, $validator) {
            $explodeBase64 = explode('data:image/',$value);
            return count($explodeBase64) > 1;
        });

        Validator::extend('availableEmail', function ($attribute, $value, $params, $validator) {
            return User::isAvailableEmail($value);
        });

        Validator::extend('rawImageS3bucketUrl', function ($attribute, $value, $params, $validator) {
            return Media::IsRawImageBucketUrl($value);
        });

        Validator::extend('youtubeOrRawImageS3BucketUrl', function ($attribute, $value, $params, $validator) {
            return Media::IsYoutubeUrl($value) || Media::IsRawImageBucketUrl($value);
        });
    }
    public function register()
    {
    }
}
