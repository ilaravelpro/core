<?php

namespace iLaravel\Core\IApp\Http\Controllers\Methods;

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
