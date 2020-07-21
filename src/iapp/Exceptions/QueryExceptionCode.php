<?php

namespace iLaravel\Core\iApp\Exceptions;

trait QueryExceptionCode
{
    public function QueryException($state, $code, $message)
    {
        return "Query Error $code:$state";
    }
}
