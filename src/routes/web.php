<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 7:11 PM
 * Copyright (c) 2021. Powered by iamir.net
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

