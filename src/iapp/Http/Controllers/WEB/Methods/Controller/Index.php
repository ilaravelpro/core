<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Index
{
    public function index(Request $request)
    {

        return $this->_index($request);
    }
}
