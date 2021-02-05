<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
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
