<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @SWG\Swagger(
 *   basePath="/v1",
 *   @SWG\Info(
 *     title="Sandwhichi API",
 *     version="",
 *     @SWG\Contact(
 *       name="daniel kim",
 *       email="bboyzepot@gmail.com",
 *     ),
 *   ),
 * )
 */

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


}
