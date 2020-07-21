<?php

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

use Illuminate\Http\Request;

trait Construct
{
    public function __construct(Request $request)
    {
        $class_name = $this->class_name(null, null, 1);
        if (!isset($this->model)) $this->model = imodal($class_name);
        if (isset($this->parentController))
            $this->parentModel = imodal($this->class_name($this->parentController, null, 1));
        if (!isset($this->resourceClass)) {
            $this->resourceClass = iresource($class_name);
            if (!class_exists($this->resourceClass)) $this->resourceClass = iresource('Resource');
        }
        if (!isset($this->resourceCollectionClass)) {
            $this->resourceCollectionClass = iresource($this->class_name(null, true, 1));
            if (!class_exists($this->resourceCollectionClass)) $this->resourceCollectionClass = iresource('Resources');
        }
        if (!isset($this->parentResourceCollectionClass)) {
            if (!isset($this->parentController))
                $this->parentResourceCollectionClass = iresource('Resource');
            else {
                $this->parentResourceCollectionClass = iresource($this->class_name($this->parentController, null, 1));
                if (!class_exists($this->parentResourceCollectionClass)) $this->parentResourceCollectionClass = iresource('Resource');
            }
        }
    }
}
