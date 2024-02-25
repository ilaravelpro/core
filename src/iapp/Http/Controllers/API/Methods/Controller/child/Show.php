<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Child;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Show
{
    public function show(Request $request, $parent, $record)
    {
        return $this->_show($request, $this->parentModel::findBySerial($parent), $this->model::findBySerial($record)?:$this->model::findByAny($record));
    }
}
