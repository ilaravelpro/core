<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $record)
    {
        return $this->_destroy($request, $record);
    }
}
