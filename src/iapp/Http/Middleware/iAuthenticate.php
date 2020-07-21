<?php

namespace iLaravel\Core\IApp\Http\Middleware;

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
