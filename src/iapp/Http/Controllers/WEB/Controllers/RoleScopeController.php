<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Controllers;

use iLaravel\Core\iApp\Http\Controllers\WEB\Controller;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Edit;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Index;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Show;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Create;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Destroy;

class RoleScopeController extends Controller
{
    use Index,
        Show,
        Create,
        Edit,
        Destroy;
}
