<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Index
{
    public function index(Request $request)
    {

        return $this->_index($request);
    }
}
