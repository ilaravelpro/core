<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 10:27 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Data
{
    public function data(Request $request)
    {
        return $this->_data($request);
    }
}
