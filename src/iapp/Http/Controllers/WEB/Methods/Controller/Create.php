<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Create
{
    public function create(Request $request)
    {
        return $this->_create($request);
    }
}
