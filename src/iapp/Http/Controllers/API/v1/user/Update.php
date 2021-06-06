<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 7:19 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;

use iLaravel\Core\iApp\File;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Update
{
    public function update(Request $request, $record)
    {
        $record = $this->model::findBySerial($record);
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        if ($request->has('email'))
            $record->_email = $request->email;
        if ($request->has('mobile'))
            $record->_mobile = $request->mobile;
        $update = $this->_update($request, $record);
        return $update;
    }
}
