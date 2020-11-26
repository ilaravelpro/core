<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/13/20, 7:06 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Auth;

use App\User;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Register
{
    public function register(Request $request)
    {
        $user = $this->findUser($request);
        if ($user) return $this->response("user duplicated", null, 401);
        $register = new $this->model;
        $register->password = Hash::make($request->input('password'));
        $register->{$this->username_method} = $request->input($this->username_method);
        $register->role = 'user';
        $register->auth_type = 'register';
        $register->save();
        return $register;
    }
}
