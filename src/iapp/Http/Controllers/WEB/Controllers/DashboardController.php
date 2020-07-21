<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Controllers;

use iLaravel\Core\iApp\Http\Controllers\WEB\Controller;

class DashboardController extends Controller
{
    public $resource = 'dashboard';
    public $views = [
        'dashboard' => 'dashboard'
    ];
}
