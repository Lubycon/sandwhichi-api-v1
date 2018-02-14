<?php

namespace App\Classes\Pager\Facades;

use Illuminate\Support\Facades\Facade;

class QueryStringParser extends Facade
{
    protected static function getFacadeAccessor() {
        return 'QueryStringParser';
    }
}