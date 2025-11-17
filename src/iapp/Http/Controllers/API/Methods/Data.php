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
            return $this->_resultIndex($args, $time, function ($model) {
                return $this->resourceDataCollectionClass ? new $this->resourceDataCollectionClass($model) : $this->resourceDataClass::collection($model);
            })->toResponse($request);
        });
        return $result;
    }
}
