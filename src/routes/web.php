<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('users', 'UserController');
    Route::resource('users/{parent}/scopes', 'UserScopeController', ['as' => 'users']);
    Route::resource('roles', 'RoleController');
    Route::resource('roles/{parent}/scopes', 'RoleScopeController', ['as' => 'roles']);
});

