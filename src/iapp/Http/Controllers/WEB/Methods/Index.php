<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Index
{
    public function _index(Request $request, $arg1 = null, $arg2 = null)
    {
        $response = self::$result->{$this->class_name(null, true, 2)} = $this->endpoint($request)->index($request, $arg1, $arg2);
        self::$result->{'a'.$this->class_name(null, true, 1)} = $this->RtoA($request, $response);
        if(isset(self::$result->{$this->class_name(null, true, 2)}->additional['meta']['parent']))
        {
            $parent_name = self::$result->{$this->class_name(null, true, 2)}->additional['meta']['parent'];
            self::$result->parent = self::$result->{$this->class_name(null, true, 2)}->additional[$parent_name];
        }
        return $this->view($request);
    }
}
