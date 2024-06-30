<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait webUpdate
{
    public function webUpdate(Request $request, $resource)
    {
        if(!$request->no_redirect || $request->redirect)
        {
            $redirect = $request->redirect;
            if(!$redirect)
            {
                $redirect = \Route::has($this->resource . '.edit')
                    ? route($this->resource . '.edit', $resource->serial ?: $resource->id)
                    : (\Route::has($this->resource . '.show') ? route($this->resource . '.show', $resource->serial ?: $resource->id) : null);
            }
            if($redirect)
            {
                $resource->additional(
                    array_replace_recursive($resource->additional, [
                        'redirect' => $redirect,
                    ])
                );
            }
        }
    }
}
