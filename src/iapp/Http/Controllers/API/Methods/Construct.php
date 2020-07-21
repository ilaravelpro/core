<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods;

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
