<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Show
{
    public function show(Request $request, $record)
    {
        return $this->_show($request, $this->model::findBySerial($record));
    }
}
