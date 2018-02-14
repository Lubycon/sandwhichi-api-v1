<?php

namespace App\Http\Middleware;

use Closure;

use Barryvdh\Cors\Util\OriginMatcher;
use Barryvdh\Cors\Stack\CorsService;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Abort;
use Log;

class CorsHandler
{
    protected $cors;
    protected $options;
    protected $request;

    public function __construct(CorsService $cors)
	{
		$this->cors = $cors;
		$this->options = config('cors');
        $this->allowedOrigins = null;
	}

	public function handle(Request $request, Closure $next)
	{
        $this->options['allowedOrigins'] = $request->getHost() === env('ADMIN_APP_HOST')
            ? $this->options['adminAllowedOrigins']
            : $this->options['allowedOrigins'];

        $this->cors = new CorsService($this->options);

        if (! $this->cors->isCorsRequest($request)) {
            return $next($request);
        }

        if ( ! $this->cors->isActualRequestAllowed($request)) {
            Abort::Error('0043','Check Origin');
        }

		$response = $next($request);

        if ($this->cors->isPreflightRequest($request)) {
            $preFlight = $this->cors->handlePreflightRequest($request);
            $response->headers->add($preFlight->headers->all());
        }
		return $this->cors->addActualRequestHeaders($response, $request);
	}
}
