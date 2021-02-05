<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Controllers\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

class ResetPasswordController extends AuthController
{

    // use ResetsPasswords;

    public function showResetForm(Request $request, $token = null)
    {
        \Data::set('token', $token);
        return $this->view('auth.login');
    }

    public function iReset(Request $request, $token)
    {
        $request->request->add(['email' => $request->username, 'token' => $token, 'password_confirmation' => $request->password]);
        return $this->reset($request);
    }

    public function __construct(Request $request)
    {
        $this->middleware('guest');
        parent::__construct($request);
    }
}
