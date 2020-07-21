<?php

Route::post('/', 'LoginController@login')->name('auth.check');
Route::get('/login', 'LoginController@showLoginForm')->name('auth.login');
Route::post('/logout', 'LoginController@logout')->name('auth.logout');
Route::get('/register', 'LoginController@showRegistrationForm')->name('auth.register');
