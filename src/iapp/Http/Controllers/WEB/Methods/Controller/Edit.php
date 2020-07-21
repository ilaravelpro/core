<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Edit
{
    public function edit(Request $request, $record)
    {
        return $this->_edit($request, $this->model::findBySerial($record));
    }
}
