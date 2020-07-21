<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Create
{
    public function create(Request $request)
    {
        return $this->_create($request);
    }
}
