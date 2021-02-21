<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;
use iLaravel\Core\iApp\File;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Store
{
    public function store(Request $request)
    {
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $store = $this->_store($request);
        return $store;
    }
}
