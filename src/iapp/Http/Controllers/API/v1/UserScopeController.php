<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 5:41 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Show;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Store;

class UserScopeController extends Controller
{
    public $parentController = UserController::class;

    use UserScope\Index,
        Show,
        Store,
        UserScope\Update,
        UserScope\Destroy,
        UserScope\Rules,
        UserScope\RequestData;
}
