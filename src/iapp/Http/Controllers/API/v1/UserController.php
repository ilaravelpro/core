<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 8:08 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Destroy;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Index;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Data;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Show;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;

class UserController extends Controller
{
    public $order_list = ['id', 'name', 'username', 'status', 'type', 'gender', 'daily' => 'created_at'];

    use Index,
        Data,
        Show,
        User\Store,
        User\Update,
        Destroy,
        User\RequestData,
        User\Filters;
}
