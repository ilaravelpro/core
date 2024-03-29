<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/26/20, 8:07 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\Vendor\Validations\iPhone;

trait UsernameMethod
{
    public function username_method(Request $request)
    {
        if ($this->username_method) return $this->username_method;
        $username = $request->input('username');
        $type = 'username';
        if (is_array($username) && $username = iPhone::parse($username))
            $username = $username['full'];
        if ($this->model::id($username)) {
            $type = 'id';
            $request->request->remove('username');;
            $request->merge([$type => $this->model::id($username)]);
        } elseif (ctype_digit($username)) {
            $type = 'mobile';
            $request->request->remove('username');;
            $request->merge([$type => $username]);
        } elseif (strpos($username, '@')) {
            $type = 'email';
            $request->request->remove('username');
            $request->merge([$type => $username]);
        }
        $this->username_method = $type;
        return $type;
    }
}
