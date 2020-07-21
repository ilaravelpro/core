<?php

namespace iLaravel\Core\Providers;

use Illuminate\Routing\Router;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
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
            'namespace' => '\iLaravel\Core\IApp\Http\Controllers\WEB\Controllers',
            'prefix' => '',
            'middleware' => 'web'
        ], function ($router) {
            require_once(i_path('routes/web.php'));
        });
    }

    public function apiRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\iLaravel\Core\IApp\Http\Controllers\API',
            'prefix' => 'api',
            'middleware' => 'api'
        ], function ($router) {
            require_once(i_path('routes/api.php'));
        });
    }

    public function authRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\iLaravel\Core\IApp\Http\Controllers\WEB\Controllers\Auth',
            'prefix' => 'auth',
            'middleware' => 'web'
        ], function ($router) {
            require_once(i_path('routes/auth.php'));
        });
    }
}
