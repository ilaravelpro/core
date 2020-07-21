<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

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
