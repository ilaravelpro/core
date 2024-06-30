<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function _destroy(Request $request, $arg1, $arg2 = null)
    {
        $response = $this->endpoint($request)->destroy($request, $arg1, $arg2);
        $model = self::$result->{$this->class_name(null, false, 2)} = $response;
        if ($model->serial ?: $model->id != $arg1->serial ?: $arg1->id)
            self::$result->parent = $arg1;
        self::$result->id = $response->serial ?: $response->id;
        self::$result->resultName = $this->class_name(null, false, 2);
        if ($model->serial ?: $model->id != $arg1->serial ?: $arg1->id)
            $route[] = $arg1->serial ?: $arg1->id;
        $route[] = $model->serial ?: $model->id;
        self::$result->module->post_action =
            \Route::has(self::$result->module->apiResource . '.index')
                ? route(self::$result->module->apiResource . '.index', $route)
                : route(self::$result->module->resource . '.index', $route);
        return $this->view($request);
    }
}
