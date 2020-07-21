<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Show
{
    public function show(Request $request, $record)
    {
        return $this->_show($request, $this->model::findBySerial($record));
    }
}
