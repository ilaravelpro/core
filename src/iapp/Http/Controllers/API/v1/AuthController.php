<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/26/20, 8:20 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Show;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

class AuthController extends Controller
{
    public $resource, $emailModel, $phoneModel, $username_method;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->model = imodal('User');
        $this->resourceClass = iresource('UserAuth', iresource('User'));
        $this->emailModel = imodal('Email');
        $this->phoneModel = imodal('Phone');
    }
    use Show;

    use Auth\Login,
        Auth\Register,
        Auth\Logout,
        Auth\Me,
        Auth\MeUpdate,
        Auth\AttemptRule,
        Auth\UsernameMethod,
        Auth\FindUser;
}
