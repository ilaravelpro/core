<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Show
{
    public function _show(Request $request, $arg1 = null, $arg2 = null)
    {
        $response = $this->endpoint($request)->show($request, $arg1, $arg2);
        self::$result->{'a'.$this->class_name(null, false, 1)} = $this->RtoA($request, $response);
        self::$result->{$this->class_name(null, false, 2)} = $response;
        self::$result->id = $response->serial ?: $response->id;
        self::$result->resultName = $this->class_name(null, false, 2);
        return $this->view($request);
    }
}
