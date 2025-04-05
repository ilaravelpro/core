<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 7:29 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use iLaravel\Core\Vendor\iRole\iRole;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Cache;

trait Data
{
    public function _data(Request $request)
    {
        $time = microtime(true);
        list($parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators, $cacheKey  ) = $this->_queryIndex(...func_get_args());
        $result = Cache::remember($cacheKey . ':data', now()->addMinutes(@$this->index_time_cached?:60), function () use ($request, $parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators, $cacheKey) {
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

            $additional['meta']['excute_time'] = '';
            $result->additional($additional);
            return  $result->toResponse($request);
        });
        $result->setContent(str_replace('"excute_time":""', '"excute_time":"'.round((microtime(true) - $time), 3).'"', $result->getContent()));
        return $result;
    }
}
