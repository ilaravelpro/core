<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Roles
{
    public function roles(Request $request)
    {
        $model = imodal('Role');
        $roles = collect();
        if (auth()->user()->role == 'admin')
            $roles->add('admin');
        $roles = $roles->merge($model::all()->pluck('name'));
        return ['data' => $roles];
    }
}
