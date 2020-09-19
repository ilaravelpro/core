<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;


trait Fillable
{
    public function fillable($action)
    {
        return isset($this->fillable[$action]) ? $this->fillable[$action] : null;
    }
}
