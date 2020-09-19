<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait FindOrFail
{
    public function findOrFail($id, $model = null)
    {
        if (!$model) $model = $this->model;
        if (gettype($id) !== 'object') {
            $query = new $model;
            $model = $query->resolveRouteBinding($id);
            if (!$model) {
                $name = explode('\\', $model);
                $name = end($name);
                throw (new ModelNotFoundException)->setModel(trim($model, '\\'), $id);
            }
            return $model;
        } else return $id;
    }
}
