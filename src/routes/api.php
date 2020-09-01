<?php
Route::namespace('v1')->prefix('v1')->group(function() {
    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('/me', 'AuthController@me')->name('api.auth.get');
        Route::post('/me', 'AuthController@me_update')->name('api.auth.update');
        Route::apiResource('users', 'UserController', ['as' => 'api']);
        Route::apiResource('users/{parent}/scopes', 'UserScopeController', [
            'as' => 'api.users'
        ]);
        Route::apiResource('roles', 'RoleController', ['as' => 'api']);
        Route::apiResource('roles/{parent}/scopes', 'RoleScopeController', [
            'as' => 'api.roles'
        ]);
        Route::prefix('data')->group(function() {
            Route::get('roles', 'DataController@roles');
            Route::get('statuses/{type?}', 'DataController@status');
            Route::get('users/{role}', 'DataController@users');
            Route::get('scopes/{type}/{parent}', 'DataController@scopes');
        });
        Route::get('/rules', function (){
            return ['data' => \iLaravel\Core\Vendor\iRole\iRoleCheck::getRules()];
        });
    });
    Route::prefix('auth')->group(function() {
        if (iconfig('auth.login')) Route::post('login', 'AuthController@login')->name('api.auth.login');
        if (iconfig('auth.logout')) Route::post('logout', 'AuthController@logout')->name('api.auth.logout');
        if (iconfig('auth.register')) Route::post('register', 'AuthController@register')->name('api.auth.register');
    });

});
