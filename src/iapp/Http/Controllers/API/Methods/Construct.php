<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use Illuminate\Http\Request;

trait Construct
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        if (!$this->clientController){
            $client = iwebcontroller(class_basename($this));
            if (class_exists($client)) $this->clientController = $client;
        }
    }
}
