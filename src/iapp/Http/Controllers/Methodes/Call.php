<?php


namespace iLaravel\Core\iApp\Http\Controllers\Methods;


trait Call
{
    public function __call($method, $parameters)
    {
        if (method_exists($this, '_' . $method)) {
            return $this->{'_' . $method}(...$parameters);
        }
        parent::__call($method, $parameters);
    }
}
