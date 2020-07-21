<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $record)
    {
        return $this->_destroy($request, $this->model::findBySerial($record));
    }
}
