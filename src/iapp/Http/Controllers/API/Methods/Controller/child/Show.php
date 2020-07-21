<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Show
{
    public function show(Request $request, $parent, $record)
    {
        return $this->_show($request, $this->parentModel::findBySerial($parent), $this->model::findBySerial($record));
    }
}
