<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 3/1/21, 12:13 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPost\iApp\Http\Controllers\API\v1\Type;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait RequestFilter
{
    public function requestFilter($request, $model, $parent, $current, $filters, $operators)
    {
        list($filters, $current) = parent::requestFilter($request, $model, $parent, $current, $filters, $operators);
        return [$filters, $current];
    }

}
