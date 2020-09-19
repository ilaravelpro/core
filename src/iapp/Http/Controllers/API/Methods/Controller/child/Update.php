<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Update
{
    public function update(Request $request, $parent, $record)
    {
        return $this->_update($request, $this->parentModel::findBySerial($parent), $this->model::findBySerial($record));
    }
}
