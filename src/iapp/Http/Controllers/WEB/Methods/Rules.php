<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Rules
{
    public function rules(Request $request, $action)
    {
        if($this->endpoint($request) && method_exists($this->endpoint($request), 'rules'))
        {
            return $this->endpoint($request)->rules(...func_get_args());
        }
        return [];
    }
}
