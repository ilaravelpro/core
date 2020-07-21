<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Update
{
    public function update(Request $request, $record)
    {
        return $this->_update($request, $this->model::findBySerial($record));
    }
}
