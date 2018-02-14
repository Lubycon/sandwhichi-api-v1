<?php

namespace App\Http\Middleware;

use App\Http\Requests\Request;
use Log;
use Closure;
use Auth;
use Abort;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Models\AccessToken;

class Authenticate
{
    protected $request;
    protected $accessToken;
    protected $except = [
        'v1/users/token/refresh'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->setRequest($request);
        $this->setAccessToken($request);
        if( !in_array($request->path(),$this->except) ) {
            if ($this->isOptionMethod() === false) {
                if (!is_null($this->accessToken)) {
                    if (!JWTAuth::setRequest($this->request)->parseToken()->authenticate()) {
                        return Abort::Error('0043', "Check Token");
                    }
                }
            }
        }
        return $next($this->request);
    }

    protected function setRequest(\Illuminate\Http\Request $request){
        return $this->request = $request;
    }

    protected function setAccessToken(\Illuminate\Http\Request $request){
        return $this->accessToken = $request->headers->get('authorization');
    }

    protected function isOptionMethod(){
        return $this->request->method() === 'OPTIONS';
    }
}