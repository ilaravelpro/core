<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;

use iLaravel\Core\iApp\File;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Update
{
    public function update(Request $request, $record)
    {
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $update = $this->_update($request, $record);
        return $update;
    }
}
