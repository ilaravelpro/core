<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Store
{
    public function store(Request $request)
    {
        return $this->_store($request);
    }
}
