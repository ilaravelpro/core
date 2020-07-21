<?php

namespace iLaravel\Core\IApp\Http\Controllers\Methods;

use iLaravel\Core\IApp\Http\Requests\iLaravel;
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
