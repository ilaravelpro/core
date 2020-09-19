<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

use Illuminate\Database\Eloquent\Model as Eloquent;

trait FindArgs
{
    public function findArgs($request, $arg1 = null, $arg2 = null)
    {
        if ($arg2) {
            $model = $this->findOrFail($arg2, $this->model);
            $parent = $this->findOrFail($arg1, $arg1 instanceof Eloquent ? get_class($arg1) : $this->parentController);
        } else {
            $model = $this->findOrFail($arg1, $this->model);
            $parent = null;
        }
        return [$parent, $model];
    }
}
