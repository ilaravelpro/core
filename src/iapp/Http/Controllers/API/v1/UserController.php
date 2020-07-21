<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Destroy;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Index;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Show;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;

class UserController extends Controller
{
    public $order_list = ['id', 'name', 'username', 'status', 'type', 'gender', 'daily' => 'created_at'];

    use Index,
        Show,
        User\Store,
        User\Update,
        Destroy,
        User\Except,
        User\Rules,
        User\RequestData,
        User\Filters,
        User\SearchQ;
}
