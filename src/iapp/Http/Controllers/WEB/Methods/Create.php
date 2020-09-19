<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

trait Create
{
    public function _create(Request $request)
    {
        self::$result->module->post_action =
            \Route::has(self::$result->module->apiResource . '.store')
                ? route(self::$result->module->apiResource . '.store')
                : route(self::$result->module->resource . '.store');
        return $this->view($request);
    }
}
