<?php

namespace iLaravel\Core\iApp\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApiIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('api')->check() || (!Auth::guard('api')->check() && !in_array(\Route::currentRouteName(), iconfig('ilaravel.apiIf.routes', [])))) {
            return app(iAuthenticate::class)->handle($request, $next, 'api');
        }
        return $next($request);
    }
}