<?php

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
