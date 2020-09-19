<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

trait Construct
{
    public function __construct(Request $request)
    {
        if (!$request->route()) return;
        parent::__construct($request);
        self::$result = new \StdClass;
        $this->designConstruct($request);
        if (!$this->endpoint){
            $endpoint = iapicontroller(class_basename($this));
            if (class_exists($endpoint)) $this->endpoint = $endpoint;
        }
    }

}
