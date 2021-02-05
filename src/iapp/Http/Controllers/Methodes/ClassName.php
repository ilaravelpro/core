<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

use Illuminate\Support\Str;

trait ClassName
{
    public function class_name($class_name = null, $plural = false, $lower = 0)
    {
        $class_name = $class_name ? : (isset($this->alias) ? $this->alias : $this);
        return class_name($class_name, $plural, $lower);
    }
}
