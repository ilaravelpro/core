<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Methods;


trait RtoA
{
    public function RtoA($request, $result)
    {
        $result = is_object($result) ? $result->toArray($request) : $result;
        foreach ($result as $key => $value) {
            if((is_object($value) && method_exists($value, 'toArray')) || is_array($value))
            {
                $result[$key] = $this->RtoA($request, $value);
            }
        }
        return $result;
    }
}
