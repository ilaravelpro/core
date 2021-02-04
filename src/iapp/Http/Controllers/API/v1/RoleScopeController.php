<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Show;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Store;

class RoleScopeController extends Controller
{
    public $parentController = RoleController::class;

    use
        RoleScope\Index,
        Show,
        Store,
        RoleScope\Update,
        RoleScope\Destroy,
        RoleScope\Rules,
        RoleScope\RequestData;
}
