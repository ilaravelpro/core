<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Methods\Controller;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $record)
    {
        return $this->_destroy($request, $record);
    }
}
