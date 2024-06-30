<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Endpoint
{
    public function endpoint(Request $request)
    {
        if(!isset($this->endpoint))
        {
            return false;
        }
        $endpoint = $this->endpoint;
        if(gettype($this->endpoint) !== 'object')
        {
            $endpoint = $this->endpoint = new $this->endpoint($request);
        }
        return $endpoint;
    }
}
