<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1;

use iLaravel\Core\IApp\Http\Controllers\API\Controller;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child\Index;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child\Show;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child\Store;

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
