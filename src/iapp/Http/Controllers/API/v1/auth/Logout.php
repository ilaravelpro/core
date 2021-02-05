<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/26/20, 10:03 AM
 * Copyright (c) 2021. Powered by iamir.net
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
