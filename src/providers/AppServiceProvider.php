<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 3:56 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Providers;

use Illuminate\Auth\RequestGuard;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

use Illuminate\Container\Container;
use Illuminate\Database\Schema\Builder;
use Illuminate\Foundation\AliasLoader;

use Illuminate\Routing\Router;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\PendingResourceRegistration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(i_path('config/ilaravel.php'), 'ilaravel.main');
        if ($this->app->request->is('api/*') || $this->app->request->ajax()) {
            if($this->app->request->is('api/*'))
            {
                $this->app->request->headers->set('Accept', 'application/json');
            }
            $this->app->bind(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                explode('.',$this->app::VERSION)[0] >= '7' ? \iLaravel\Core\iApp\Exceptions\ExceptionHandler7::class : \iLaravel\Core\iApp\Exceptions\ExceptionHandler::class
            );
        }
        if($this->app->runningInConsole())
        {
            $this->publishes([
                i_path('public') => public_path('/'),
                i_path('resources') => resource_path('/'),
            ]);
            if (iconfig('database.migrations.include', true)) $this->loadMigrationsFrom(i_path('database/migrations'));
            $this->commands([
                \iLaravel\Core\iApp\Console\Commands\RemoveSanctum::class,
            ]);
        }
        $this->registerRoutes();
        View::addLocation(i_path('resources/views'));

        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages) {
            return new \iLaravel\Core\iApp\Http\Validators\iLaravel($translator, $data, $rules, $messages);
        });
        Schema::defaultStringLength(191);

        Builder::macro('blueprint', function($blueprint)
        {
            $this->resolver = function ($connection, $table, $callback) use ($blueprint) {
                return Container::getInstance()->make($blueprint, compact('connection', 'table', 'callback'));
            };
            return $this;
        });

        Schema::macro('smartCreate', function (string $tableName, \Closure $definition, $blueprintClass = null) {
            $blueprintClass = $blueprintClass?:\Illuminate\Database\Schema\Blueprint::class;
            if (Schema::hasTable($tableName)) {
                $blueprint = new ($blueprintClass)(Schema::getConnection(), $tableName);
                $definition($blueprint);
                $existingColumns = Schema::getColumnListing($tableName);
                $existingIndexes = Schema::getIndexes($tableName);
                $existingIndexesNames = array_merge(...array_column($existingIndexes, 'columns'));
                $lastCol = in_array("status", $existingColumns) ? "status" : (in_array("created_at", $existingColumns) ? "created_at" : last($existingColumns));
                Schema::table($tableName, function ($table) use ($blueprint, $existingColumns, $existingIndexes, $existingIndexesNames, $tableName, $lastCol) {
                    $commands = $blueprint->getCommands();
                    foreach ($columns = $blueprint->getColumns() as $index => $column) {
                        if (($name = $column->get('name')) && (($is_new = !in_array($name, $existingColumns)) ||
                                (class_exists(\Doctrine\DBAL\Types\Type::class) && Schema::getColumnType($tableName, $name) !== $column->get('type')))) {
                            $params = array_filter($column->getAttributes(), fn($k) => !in_array($k, ["type", "name"])
                                && !(in_array($k, ["unique", "index", "primary"]) && in_array($name, $existingIndexesNames)), ARRAY_FILTER_USE_KEY);

                            if (in_array($column->get('type'), ["char", "string"]) && empty($params["length"])) $params["length"] = 191;
                            if ($is_new) $table->addColumn($column->get('type'), $column->get('name'), $params)
                                ->{empty($existingColumns[$index]) && $index < array_key_last($columns) ? "before" : "after"}(@$existingColumns[$index]?:$lastCol);
                            else
                                $table->addColumn($column->get('type'), $column->get('name'), $params)->change();

                        }
                    }
                    foreach ($commands as $command) {
                        $commandName = $command->get('name');
                        if (in_array($commandName, ['index', 'unique', 'foreign', 'primary', 'dropIndex', 'dropUnique', 'dropForeign', 'dropPrimary'])) {
                            if (strpos($commandName, 'drop') === 0) {
                                try {
                                    $table->{$commandName}(...$command->getAttributes());
                                } catch (\Throwable $e) {}
                            } else {
                                $attributes = $command->getAttributes();
                                $indexName = $attributes[0] ?? null;
                                $columns = is_array(@$attributes[0]) ? $attributes[0] : (@$attributes['columns']?: []);
                                if (!$indexName && $columns)
                                    $indexName = $tableName . '_' . implode('_', $columns) . '_' . $commandName;
                                $exists = false;
                                foreach ($existingIndexes as $idx) {
                                    if (($indexName && $idx['name'] === $indexName) ||
                                        (array_diff($idx['columns'], $columns) === [] && $idx['type'] === $commandName)) {
                                        $exists = true;
                                        break;
                                    }
                                }

                                if (!$exists) {
                                    try {
                                        $table->{$commandName}(...$attributes);
                                    } catch (\Throwable $e) {}
                                }
                            }
                        } else {
                            try {
                                $table->{$commandName}(...$command->getAttributes());
                            } catch (\Throwable $e) {}
                        }
                    }
                });
            }else
                Schema::blueprint($blueprintClass)->create($tableName, $definition);
        });
        $this->app->singleton('i_types', function(){
            return imodal('Type')::all();
        });

    }

    public function register()
    {
        $this->app->booting(function () {
            $this->registerMacros();
            $this->app->bind('Illuminate\Routing\ResourceRegistrar', '\iLaravel\Core\iApp\Http\Registrars\ResourceRegistrar');
            $loader = AliasLoader::getInstance();
            $loader->alias('iRole', \iLaravel\Core\Vendor\iRole\iRole::class);
        });
    }

    public function registerRoutes() {
        $router = $this->app['router'];
        $router->aliasMiddleware('authIf', \iLaravel\Core\iApp\Http\Middleware\iAuthIf::class);
        $router->pushMiddlewareToGroup('api', \iLaravel\Core\iApp\Http\Middleware\iResponse::class);
        $router->pushMiddlewareToGroup('web', \iLaravel\Core\iApp\Http\Middleware\iResponse::class);
        $router->aliasMiddleware('auth', \iLaravel\Core\iApp\Http\Middleware\iAuthenticate::class);
    }

    public function registerMacros() {
        RequestGuard::macro('isAdmin', function(){
            return $this->user->isAdmin();
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
