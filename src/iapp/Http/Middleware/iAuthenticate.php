<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Middleware;

use Closure;
use App\Http\Middleware\Authenticate as Middleware;

class iAuthenticate extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        if (!in_array('apiIf', $guards) || ($request->server('HTTP_AUTHORIZATION') && in_array($request->server('HTTP_AUTHORIZATION'), ['null', 'Bearer null']) === false))
        {
            return parent::authenticate($request, $guards);
        }
    }
}
