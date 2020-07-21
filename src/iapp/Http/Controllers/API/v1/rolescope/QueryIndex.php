<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\Vendor\iRole\iRole;

trait QueryIndex
{
    public function queryIndex(Request $request, $parent)
    {
        $scopes = iRole::scopes($parent->scopes, $this->model);
        return [$parent, $scopes];
    }
}
