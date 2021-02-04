<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    if (iconfig('routes.web.users.status')){
        Route::resource('users', 'UserController');
        Route::resource('users/{parent}/scopes', 'UserScopeController', ['as' => 'users']);
    }
    if (iconfig('routes.web.roles.status')){
        Route::resource('roles', 'RoleController');
        Route::resource('roles/{parent}/scopes', 'RoleScopeController', ['as' => 'roles']);
    }
});

