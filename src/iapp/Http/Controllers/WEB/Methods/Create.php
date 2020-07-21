<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Methods;

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
