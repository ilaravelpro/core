<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\UserScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\iApp\Methods\Scopes;

trait Index
{
    public function Index(Request $request, $parent)
    {
        return Scopes::parse($request, 'user', $parent);
    }
}
