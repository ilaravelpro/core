<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 8:14 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Auth\AuthenticationException;

trait Login
{
    public function login(Request $request)
    {
        if (!config('auth.enter.login', true)) {
            throw new AuthenticationException('login disabled');
        }
        $this->username_method($request);
        if (!$this->model::where($this->username_method, $request->input($this->username_method))->first() && iconfig('auth.auto_register')) {
            return $this->register($request);
        }
        if (auth()->attempt($this->attempt_rule($request))) {
            $user = $this->show($request, auth()->user()->serial);
            if ($user->status != 'active') {
                throw new AuthenticationException('not active');

            }
            $token = $user->createToken('API')->accessToken;
            $user->additional(array_merge_recursive($user->additional, [
                'additional' => ['token' => $token]
            ]));
            $this->statusMessage = 'succsess';
            return $user;
        } else {
            throw new AuthenticationException('Username or password is not match');
        }
    }
}
