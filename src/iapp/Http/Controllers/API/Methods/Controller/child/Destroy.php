<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $parent, $record)
    {
        return $this->_destroy($request, $this->parentModel::findBySerial($parent), $this->model::findBySerial($record));
    }
}
