<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
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
