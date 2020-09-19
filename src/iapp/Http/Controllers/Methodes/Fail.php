<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait Fail
{
    public function fail($model = null, $id = null)
    {
        if (!$model) {
            $model = $this->model;
        }
        throw (new ModelNotFoundException)->setModel(trim($model, '\\'), $id);
    }
}
