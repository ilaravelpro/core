<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Store
{
    public function store(Request $request)
    {
        return $this->_store($request);
    }
}
