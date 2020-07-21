<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

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
