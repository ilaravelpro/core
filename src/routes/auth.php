<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

if (iconfig('auth.login')) Route::post('/', 'LoginController@login')->name('auth.check');
if (iconfig('auth.login')) Route::get('/login', 'LoginController@showLoginForm')->name('auth.login');
if (iconfig('auth.logout')) Route::post('/logout', 'LoginController@logout')->name('auth.logout');
if (iconfig('auth.register')) Route::get('/register', 'LoginController@showRegistrationForm')->name('auth.register');
