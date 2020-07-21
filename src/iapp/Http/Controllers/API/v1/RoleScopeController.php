<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1;

use iLaravel\Core\IApp\Http\Controllers\API\Controller;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child\Index;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child\Show;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child\Store;

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
