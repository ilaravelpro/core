<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Controllers;

use iLaravel\Core\IApp\Http\Controllers\WEB\Controller;
use iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller\Edit;
use iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller\Index;
use iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller\Show;
use iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller\Create;
use iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller\Destroy;

class UserController extends Controller
{
    use Index,
        Show,
        Create,
        Edit,
        Destroy;
}
