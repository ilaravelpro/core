<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/26/20, 8:45 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

trait Login
{
    public function login(Request $request)
    {
        if (!config('auth.enter.login', true))
            throw new AuthenticationException('login disabled');
        $user = $this->findUser($request);
        if (!$user && iconfig('auth.auto_register'))
            $user =  $this->register($request);
        if ($user && Hash::check($request->input('password'), $user->password)) {
            auth()->login($user);
            $user = $this->show($request, auth()->user()->serial);
            if ($user->status != 'active')
                throw new AuthenticationException('not active');
            $token = $user->createToken('API')->accessToken;
            $user->additional(array_merge_recursive($user->additional, [
                'additional' => ['token' => $token]
            ]));
            $this->statusMessage = 'You have successfully logged in.';
            return $user;
        } else {
            throw new AuthenticationException('Username or password is not match');
        }
    }
}
