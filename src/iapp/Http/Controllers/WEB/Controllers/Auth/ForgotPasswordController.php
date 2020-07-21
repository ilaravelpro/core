<?php

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
