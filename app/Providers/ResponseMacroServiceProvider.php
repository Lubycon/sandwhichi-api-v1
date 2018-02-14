<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use Request;
use Log;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('success', function ($data=null) use ($factory) {
            $response = response()->json([
                'status' => (object)array(
                    'code' => '0000',
                    'msg' => "success",
                    "devMsg" => ''
                ),
                'result' => isset($data) ? $data : null
            ]);

            return $response;
        });
        $factory->macro('error', function ($data) use ($factory) {
            $code = $data['code'];
            $config = config('error.'.$data['code']);
            $msg = $config->msg;
            $httpCode = isset($data['httpCode']) ? $data['httpCode'] : $config->httpCode;
            $devMsg = isset($data['devMsg']) && env('APP_ENV') != 'production'
                ? $data['devMsg']
                : '';

            $request = Request::instance();
            $response = response()->json([
                'status' => (object)array(
                    'code' => $code,
                    'msg' => $msg,
                    "devMsg" => $devMsg
                ),
                'result' => null
            ],$httpCode);
            $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
            return $response;
        });
    }
    public function register()
    {
    }
}
