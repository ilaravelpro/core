<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/13/20, 6:48 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Show;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $username_method;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->model = imodal('User');
        $this->resourceClass = iresource('User');
    }
    use Show;

    use Auth\Login,
        Auth\Register,
        Auth\Logout,
        Auth\Me,
        Auth\MeUpdate,
        Auth\AttemptRule,
        Auth\UsernameMethod;
}
