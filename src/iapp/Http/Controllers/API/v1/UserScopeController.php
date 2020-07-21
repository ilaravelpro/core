<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Index;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Show;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child\Store;

class UserScopeController extends Controller
{
    public $parentController = UserController::class;

    use Index,
        Show,
        Store,
        UserScope\Update,
        UserScope\Destroy,
        UserScope\Rules,
        UserScope\RequestData,
        UserScope\QueryIndex;
}
