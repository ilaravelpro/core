<?php


namespace iLaravel\Core\iApp\Http\Controllers\Methods;


trait Fillable
{
    public function fillable($action)
    {
        return isset($this->fillable[$action]) ? $this->fillable[$action] : null;
    }
}
