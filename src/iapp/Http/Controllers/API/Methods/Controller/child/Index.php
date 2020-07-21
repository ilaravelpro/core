<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Index
{
    public function index(Request $request, $parent)
    {
        return $this->_index($request, $this->parentModel::findBySerial($parent));
    }
}
