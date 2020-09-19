<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\Vendor\iRole\iRole;

trait QueryIndex
{
    public function queryIndex(Request $request, $parent)
    {
        $scopes = iRole::scopes($parent->scopes, $this->model);
        return [$parent, $scopes];
    }
}
