<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Update
{
    public function update(Request $request, $record)
    {
        return $this->_update($request, $this->model::findBySerial($record));
    }
}
