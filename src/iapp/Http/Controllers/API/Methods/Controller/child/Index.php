<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/11/20, 5:26 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Index
{
    public function index(Request $request, $parent)
    {
        return $this->_index($request, $this->parentModel::findBySerial($parent));
    }
}
