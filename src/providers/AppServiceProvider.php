<?php

namespace iLaravel\Core\Providers;

use Illuminate\Auth\RequestGuard;

use Illuminate\Http\Request;

use Illuminate\Foundation\AliasLoader;

use Illuminate\Routing\Router;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\PendingResourceRegistration;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(i_path('config/ilaravel.php'), 'ilaravel.main');
        $this->registerMacros();
        if ($this->app->request->is('api/*') || $this->app->request->ajax()) {
            if($this->app->request->is('api/*'))
            {
                $this->app->request->headers->set('Accept', 'application/json');
            }
            $this->app->bind(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                substr($this->app::VERSION, 0, 1) == '7' ? \iLaravel\Core\iApp\Exceptions\ExceptionHandler7::class : \iLaravel\Core\iApp\Exceptions\ExceptionHandler::class
            );

        }
        if($this->app->runningInConsole())
        {
            $this->publishes([
                i_path('public') => public_path('/'),
                i_path('resources') => resource_path('/'),
            ]);
            if (iconfig('database.migrations.include', true)) $this->loadMigrationsFrom(i_path('database/migrations'));
        }
        $this->registerRoutes();
        View::addLocation(i_path('resources/views'));

        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages) {
            return new \iLaravel\Core\iApp\Http\Validators\iLaravel($translator, $data, $rules, $messages);
        });
        Schema::defaultStringLength(191);

    }
    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('iRole', \iLaravel\Core\Vendor\iRole\iRole::class);
        });
    }

    public function registerRoutes() {
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('api', \iLaravel\Core\iApp\Http\Middleware\iResponse::class);
        $router->pushMiddlewareToGroup('web', \iLaravel\Core\iApp\Http\Middleware\iResponse::class);
        $router->aliasMiddleware('auth', \iLaravel\Core\iApp\Http\Middleware\iAuthenticate::class);
    }

    public function registerMacros() {
        RequestGuard::macro('isAdmin', function(){
            return $this->user->isAdmin();
        });

        \Route::macro('authIf', function(){
            return app('request')->header('authorization') ? 'auth:api' : 'api';
        });

        Router::macro('mResource', function($name, $controller, array $options = []){
            if(!isset($options['except']))
            {
                $options['except'] = ['store', 'update', 'destroy'];
            }
            else
            {
                $options['except'] = array_merge_recursive($options['except'], ['store', 'update', 'destroy']);
            }
            if (!isset($options['as'])) {
                $options['as'] = 'dashboard';
            }
            if ($this->container && $this->container->bound(ResourceRegistrar::class)) {
                $registrar = $this->container->make(ResourceRegistrar::class);
            } else {
                $registrar = new ResourceRegistrar($this);
            }

            return new PendingResourceRegistration(
                $registrar,
                $name,
                $controller,
                $options
            );
        });

        Request::macro('webAccess', function(){
            return $this->headers->get('Web-Access') ? true : false;
        });

        Cache::macro('getJson', function(){
            $get = Cache::get(...func_get_args());
            return $get ? json_decode($get) : $get;
        });
    }
}
