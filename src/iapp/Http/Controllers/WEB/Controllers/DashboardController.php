<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Controllers;

use iLaravel\Core\IApp\Http\Controllers\WEB\Controller;

class DashboardController extends Controller
{
    public $resource = 'dashboard';
    public $views = [
        'dashboard' => 'dashboard'
    ];
}
