<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Edit
{
    public function edit(Request $request, $record)
    {
        return $this->_edit($request, $this->model::findBySerial($record));
    }
}
