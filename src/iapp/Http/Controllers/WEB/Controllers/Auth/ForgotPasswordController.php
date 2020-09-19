<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

class ForgotPasswordController extends AuthController
{

    use SendsPasswordResetEmails;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('guest');
    }
}
