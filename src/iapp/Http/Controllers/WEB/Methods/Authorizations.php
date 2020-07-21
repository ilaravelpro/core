<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

trait Authorizations
{
    public function authorizations(Request $request, $action)
    {
        if (method_exists($this->endpoint($request), 'authorizations')) {
            return $this->endpoint($request)->authorizations(...func_get_args());
        }
        return true;
    }
}
