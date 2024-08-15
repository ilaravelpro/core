<?php

namespace iLaravel\Core\iApp\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class iAuthIf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $has_action_controller = in_array($request->route()->getActionMethod(), @$request->route()->getController()->authIf?:[]);
        $has_action_config = in_array(\Route::currentRouteName(), iconfig('authIf.routes', []));
        if (Auth::guard('api')->check() ||
            (
                !Auth::guard('api')->check() &&
                ($has_action_controller ? !$has_action_controller : !$has_action_config)
            )
        ) {
            return app(iAuthenticate::class)->handle($request, $next, ...(count($guards) ? $guards : ['api']));
        }
        return $next($request);
    }
}