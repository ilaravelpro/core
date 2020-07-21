<?php

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

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
