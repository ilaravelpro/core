<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

class ForgotPasswordController extends AuthController
{

    use SendsPasswordResetEmails;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('guest');
    }
}
