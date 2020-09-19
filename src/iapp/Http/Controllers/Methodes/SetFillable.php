<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

trait SetFillable
{
    public function setFillable($action, $parameters)
    {
        $this->fillable[$action] = $parameters;
    }

}
