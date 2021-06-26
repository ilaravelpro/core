<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 1:27 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Me
{
    public function me(Request $request)
    {
        $this->statusMessage = 'me';
        $user = new $this->resourceClass(auth()->check() ? $this->model::findBySerial(\Auth::user()->serial) : $this->model::guest());
        $user->additional(array_merge_recursive($user->additional, [
            'additional' => [ 'token' => $request->bearerToken() ]
        ]));
        return $user;
    }
}
