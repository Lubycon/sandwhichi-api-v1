<?php
namespace App\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Abort;

trait AuthorizesRequestsOverLoad {
    use AuthorizesRequests;

    public function authorizeAtGate(Gate $gate, $ability, $arguments)
    {
        try {
            return $gate->authorize($ability, $arguments);
        } catch (UnauthorizedException $e) {
            Abort::Error('0043','authorizeAtGate');
        }
    }
}