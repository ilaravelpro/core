<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\UserScope;

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
