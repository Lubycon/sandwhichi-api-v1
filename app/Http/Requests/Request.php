<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Abort;
use Log;

abstract class Request extends FormRequest
{
    public function forbiddenResponse()
    {
        Abort::Error('0043');
    }
    public function response(array $errors)
    {
//        Log::info($errors);
        Abort::Error('0051',$errors);
    }
}
