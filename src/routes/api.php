<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/13/20, 6:07 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

Route::namespace('v1')->prefix('v1')->group(function () {
    Route::group(['middleware' => ['auth:api']], function () {
        if (iconfig('auth.get')) Route::get('/me', 'AuthController@me')->name('api.auth.get');
        if (iconfig('auth.update')) Route::post('/me', 'AuthController@me_update')->name('api.auth.update');
        Route::apiResource('users', 'UserController', ['as' => 'api']);
        Route::apiResource('users/{parent}/scopes', 'UserScopeController', [
            'as' => 'api.users'
        ]);
        Route::apiResource('roles', 'RoleController', ['as' => 'api']);
        Route::apiResource('roles/{parent}/scopes', 'RoleScopeController', [
            'as' => 'api.roles'
        ]);
        Route::prefix('data')->group(function () {
            Route::get('roles', 'DataController@roles');
            Route::get('statuses/{type?}', 'DataController@status');
            Route::get('users/{role?}', 'DataController@users');
            Route::get('scopes/{type}/{parent}', 'DataController@scopes');
        });
        Route::get('/rules', function () {
            $rules = \iLaravel\Core\Vendor\iRole\iRoleCheck::getRulesUnique();
            return ['data' => function_exists('i_get_rules_items') ? i_get_rules_items($rules) : $rules];
        });
    });
    Route::prefix('auth')->group(function () {
        if (iconfig('auth.login')) Route::post('login', 'AuthController@login')->name('api.auth.login');
        if (iconfig('auth.logout')) Route::post('logout', 'AuthController@logout')->name('api.auth.logout');
        if (iconfig('auth.register')) Route::post('register', 'AuthController@register')->name('api.auth.register');
    });

});
