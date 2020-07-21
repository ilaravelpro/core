<?php

namespace iLaravel\Core\IApp\Exceptions;

trait QueryExceptionCode
{
    public function QueryException($state, $code, $message)
    {
        return "Query Error $code:$state";
    }
}
