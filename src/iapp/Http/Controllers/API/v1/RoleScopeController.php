<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Index;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Show;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Store;

class RoleScopeController extends Controller
{
    public $parentController = RoleController::class;

    use Index,
        Show,
        Store,
        RoleScope\Update,
        RoleScope\Destroy,
        RoleScope\Rules,
        RoleScope\RequestData,
        RoleScope\QueryIndex;
}
