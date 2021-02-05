<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
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
