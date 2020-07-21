<?php

namespace iLaravel\Core\Providers;

//use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];
    public function boot()
    {
        $this->registerPolicies();

        Config::set([
            'auth.guards.api.driver' => 'passport'
        ]);
        Config::set([
            'auth.guards.apiIf' => Config::get('auth.guards.api')
        ]);
        // Set Passport route
        //Passport::routes();
        Passport::withoutCookieSerialization();
        // Set expire date
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Gate::define('irole', function ($user, $request, $irole, ...$args) {
            return Gate::allows($irole,  $args);
        });

        Gate::resource('users', 'iLaravel\Core\IApp\Http\Policies\UserPolicy');
        Gate::resource('users.scopes', 'iLaravel\Core\IApp\Http\Policies\UserScopePolicy');
        Gate::resource('roles', 'iLaravel\Core\IApp\Http\Policies\RolePolicy');
        Gate::resource('roles.scopes', 'iLaravel\Core\IApp\Http\Policies\RoleScopePolicy');
    }
}
