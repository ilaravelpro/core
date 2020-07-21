<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

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
