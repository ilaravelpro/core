<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;


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
