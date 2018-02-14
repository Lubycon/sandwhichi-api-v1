<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Abort;
use Log;

use App\Models\User;
use App\Models\AdminProcessLog;

class AdminAuthenticate
{
	public function handle(Request $request, Closure $next)
	{
        if ( User::isUser() && User::isActive() && User::isAdmin() ) {
            AdminProcessLog::createLog($request);
            return $next($request);
        }else{
            Abort::Error('0043','You are not Admin');
        }
	}
}
