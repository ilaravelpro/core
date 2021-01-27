<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/17/20, 9:29 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use iLaravel\Core\Vendor\iRole\iRole;
use Illuminate\Http\Request;

trait Data
{
    public function _data(Request $request)
    {
        list($parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators) = $this->_queryIndex(...func_get_args());
        $result = $this->resourceDataCollectionClass ? new $this->resourceDataCollectionClass($model) : $this->resourceDataClass::collection($model);
        $additional = [];
        if ($parent) {
            $parentController = isset($this->parentController) ? $this->parentController : get_class($parent);
            $additional[$this->class_name($parentController, null, 2)] = new $this->parentResourceDataCollectionClass($parent);
            $additional['meta'] = [
                'parent' => $this->class_name($parentController, null, 2)
            ];
        }

        if (!isset($additional['meta'])) {
            $additional['meta'] = [];
        }
        $additional['meta']['orders'] = [
            'allowed' => $order_list ?: [],
            'current' => $current_order ?: [],
            'default' => $default_order,
        ];
        $additional['meta']['filters'] = [
            'allowed' => $filters ?: [],
            'current' => $current_filter ?: [],
            'operators' => $operators,
        ];

        $result->additional($additional);
        return $result;
    }
}
