<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Index
{
    public function index(Request $request, $parent)
    {
        return $this->_index($request, $this->parentModel::findBySerial($parent));
    }
}
