<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 6:53 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Providers;

use Illuminate\Routing\Router;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        \Route::macro('authIf', function(){
            if(!app('request')->header('authorization')) {
                $userClass = imodal('User');
                Auth::setUser($userClass::guest());
            }
            $auth = app('request')->header('authorization');
            $auth = str_replace(['null', 'Bearer null'], '', $auth);
            return $auth ? 'auth:api' : 'api';
        });
        \Route::macro('authOr', function(){
            $auth = app('request')->header('authorization');
            $auth = str_replace(['null', 'Bearer null'], '', $auth);
            return $auth ? 'auth:api' : 'api';
        });
    }

    public function register()
    {
        parent::register();
    }

    public function map(Router $router)
    {
        if (iconfig('routes.web.status', true)) $this->webRoutes($router);
        if (iconfig('routes.api.status', true)) $this->apiRoutes($router);
        if (iconfig('routes.auth.status', true)) $this->authRoutes($router);
    }

    public function webRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\iLaravel\Core\iApp\Http\Controllers\WEB\Controllers',
            'prefix' => '',
            'middleware' => 'web'
        ], function ($router) {
            require_once(i_path('routes/web.php'));
        });
    }

    public function apiRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\iLaravel\Core\iApp\Http\Controllers\API',
            'prefix' => 'api',
            'middleware' => 'api'
        ], function ($router) {
            require_once(i_path('routes/api.php'));
        });
    }

    public function authRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\iLaravel\Core\iApp\Http\Controllers\WEB\Controllers\Auth',
            'prefix' => 'auth',
            'middleware' => 'web'
        ], function ($router) {
            require_once(i_path('routes/auth.php'));
        });
    }
}
