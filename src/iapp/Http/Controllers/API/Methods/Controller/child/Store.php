<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Store
{
    public function store(Request $request, $parent)
    {
        return $this->_store($request, $this->parentModel::findBySerial($parent));
    }
}
