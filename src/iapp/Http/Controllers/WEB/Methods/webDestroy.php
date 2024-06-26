<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait webDestroy
{
    public function webDestroy(Request $request, $resource, $arg1, $arg2 = null)
    {
        $parent = null;
        if (isset($resource->additional['meta']['parent'])) {
            $parent_name = $resource->additional['meta']['parent'];
            $parent = $resource->additional[$parent_name];
        }
        if (!$request->no_redirect || $request->redirect) {
            $redirect = $request->redirect;
            if (!$redirect) {
                $redirect = \Route::has($this->resource . '.index')
                    ? route($this->resource . '.index', $parent ? ($parent->serial ?: $parent->id) : null)
                    : null;
            }
            if ($redirect) {
                $resource->additional(
                    array_replace_recursive($resource->additional, [
                        'redirect' => $redirect,
                    ])
                );
            }
        }
    }
}
