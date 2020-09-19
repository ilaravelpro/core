<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Controllers;

use iLaravel\Core\iApp\Http\Controllers\WEB\Controller;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Edit;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Index;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Show;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Create;
use iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller\Destroy;

class PostController extends Controller
{
    use Index,
        Show,
        Create,
        Edit,
        Destroy;
}
