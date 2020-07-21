<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Store
{
    public function store(Request $request, $parent)
    {
        return $this->_store($request, $this->parentModel::findBySerial($parent));
    }
}
