<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Edit
{
    public function _edit(Request $request, $arg1, $arg2 = null)
    {
        $response = $this->endpoint($request)->show($request, $arg1, $arg2);
        self::$result->{'a'.$this->class_name(null, false, 1)} = $this->RtoA($request, $response);
        $model = self::$result->{$this->class_name(null, false, 2)} = $response;
        self::$result->id = $response->serial ?: $response->id;
        self::$result->resultName = $this->class_name(null, false, 2);
        self::$result->module->post_action =
            \Route::has(self::$result->module->apiResource . '.update')
                ? route(self::$result->module->apiResource . '.update', $model->serial ?: $model->id)
                : route(self::$result->module->resource . '.update', $model->serial ?: $model->id);
        return $this->view($request);
    }
}
