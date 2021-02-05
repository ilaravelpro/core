<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Controllers;

use iLaravel\Core\iApp\Http\Controllers\WEB\Controller;

class DashboardController extends Controller
{
    public $resource = 'dashboard';
    public $views = [
        'dashboard' => 'dashboard'
    ];
}
