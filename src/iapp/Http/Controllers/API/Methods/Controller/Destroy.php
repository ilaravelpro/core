<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $record)
    {
        return $this->_destroy($request, $this->model::findBySerial($record));
    }
}
