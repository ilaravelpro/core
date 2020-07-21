<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

trait Rules
{
    public function rules(Request $request, $action)
    {
        if(method_exists($this->endpoint($request), 'rules'))
        {
            return $this->endpoint($request)->rules(...func_get_args());
        }
        return [];
    }
}
