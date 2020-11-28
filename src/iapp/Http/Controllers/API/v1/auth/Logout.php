<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Logout
{
    public function logout(Request $request)
    {
        $user = $this->show($request, auth()->user()->serial);
        $request->user('api')->token()->revoke();
        $this->statusMessage = 'Logout Success.';
        return $user;
    }
}
