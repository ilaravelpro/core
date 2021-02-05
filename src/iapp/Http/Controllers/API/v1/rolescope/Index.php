<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 5:41 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\iApp\Methods\Scopes;

trait Index
{
    public function index(Request $request, $parent)
    {
        return Scopes::parse($request, 'role', $parent);
    }
}
