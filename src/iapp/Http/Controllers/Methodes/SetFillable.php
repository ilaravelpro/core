<?php

namespace iLaravel\Core\IApp\Http\Controllers\Methods;

trait SetFillable
{
    public function setFillable($action, $parameters)
    {
        $this->fillable[$action] = $parameters;
    }

}
