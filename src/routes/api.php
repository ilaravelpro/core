<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 7:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

Route::namespace('v1')->prefix('v1')->group(function () {
    Route::group(['middleware' => ['auth:api']], function () {
        if (iconfig('auth.update')) Route::post('/me', 'AuthController@me_update')->name('api.auth.update');
        if (iconfig('routes.api.users.status')){
            Route::apiResource('users', 'UserController', ['as' => 'api']);
            Route::apiResource('users/{parent}/scopes', 'UserScopeController', [
                'as' => 'api.users'
            ]);
        }
        if (iconfig('routes.api.roles.status')){
            Route::apiResource('roles', 'RoleController', ['as' => 'api']);
            Route::apiResource('roles/{parent}/scopes', 'RoleScopeController', [
                'as' => 'api.roles'
            ]);
        }
        if (iconfig('auth.logout')) Route::post('auth/logout', 'AuthController@logout')->name('api.auth.logout');
    });
    Route::group(['middleware' => ['auth:apiIf']], function () {
        Route::prefix('data')->group(function () {
            Route::get('statuses/{type?}', 'DataController@status')->name('api.data.status');
        });
        \Route::get('/rules', function () {
            $rules = \iLaravel\Core\Vendor\iRole\iRoleCheck::getRulesUnique();
            return ['data' => function_exists('i_get_rules_items') ? i_get_rules_items($rules) : $rules];
        })->name('api.auth.rules');
        if (iconfig('auth.get')) Route::get('/me', 'AuthController@me')->name('api.auth.get');
    });
    Route::prefix('auth')->group(function () {
        if (iconfig('auth.login')) Route::post('login', 'AuthController@login')->name('api.auth.login');
        if (iconfig('auth.register')) Route::post('register', 'AuthController@register')->name('api.auth.register');
    });
});
