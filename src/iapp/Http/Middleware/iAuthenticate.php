<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/16/20, 7:11 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Middleware;

use Closure;
use App\Http\Middleware\Authenticate as Middleware;

class iAuthenticate extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        if (!in_array('apiIf', $guards) || $request->server('HTTP_AUTHORIZATION'))
        {
            return parent::authenticate($request, $guards);
        }
    }
}
