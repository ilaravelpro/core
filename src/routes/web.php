<?php

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('users', 'UserController');
    Route::resource('users/{parent}/scopes', 'UserScopeController', ['as' => 'users']);
    Route::resource('roles', 'RoleController');
    Route::resource('roles/{parent}/scopes', 'RoleScopeController', ['as' => 'roles']);
});

