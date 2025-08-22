<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 7:29 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Cache;

trait Data
{
    public function _data(Request $request)
    {
        $args = func_get_args();
        $time = microtime(true);
        $result = Cache::remember($this->indexKey($request), $this->index_mts_cached, function () use ($request, $args, $time) {
            list($parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators, $cacheKey  ) = $this->_queryIndex(...$args);
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

            $additional['meta']['execute_time'] = round((microtime(true) - $time), 3);
            $result->additional($additional);
            return $result;
        });
        $result->additional["meta"]['execute_time'] = round((microtime(true) - $time), 3);
        return $result;
    }
}
