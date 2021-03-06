<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Exceptions;

trait QueryExceptionCode
{
    public function QueryException($state, $code, $message)
    {
        return "Query Error $code:$state";
    }
}
