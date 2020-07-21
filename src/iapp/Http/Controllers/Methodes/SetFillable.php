<?php

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

trait SetFillable
{
    public function setFillable($action, $parameters)
    {
        $this->fillable[$action] = $parameters;
    }

}
