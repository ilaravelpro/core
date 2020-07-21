<?php

namespace iLaravel\Core\IApp\Http\Controllers\Methods;

use Illuminate\Support\Str;

trait ClassName
{
    public function class_name($class_name = null, $plural = false, $lower = 0)
    {
        $class_name = $class_name ? : (isset($this->alias) ? $this->alias : $this);
        return class_name($class_name, $plural, $lower);
    }
}
