<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 11:12 PM
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Construct
{
    public function __construct(Request $request)
    {
        $class_name = $this->class_name(null, null, 1);
        $action = 'index';
        if ($request->route()){
            if (isset($request->route()->getAction()['method'])){
                $action = $request->route()->getAction()['method'];
            }elseif ($this->route()->getAction('as')) {
                $aAaction = explode('.', $action);
                $action = $aAaction[0];
            }
        }
        if (!isset($this->model)) $this->model = imodal($class_name);
        if (isset($this->parentController))
            $this->parentModel = imodal($this->class_name($this->parentController, null, 1));

        if (!isset($this->resourceClass)) {
            $this->resourceClass = iresource($class_name);
            if (!class_exists($this->resourceClass)) $this->resourceClass = iresource('Resource');
        }

        if ($action == 'data' && !isset($this->resourceDataClass)) {
            $this->resourceDataClass = iresource($class_name . "Data");
            if (!class_exists($this->resourceDataClass)) $this->resourceDataClass = iresource('ResourceData');
        }
        if (isset($this->resourceCollectionClass) && $this->resourceCollectionClass == 'self') {
            $this->resourceCollectionClass = null;
        } elseif (!isset($this->resourceCollectionClass)) {
            $this->resourceCollectionClass = iresource($this->class_name(null, true, 1));
            if (!class_exists($this->resourceCollectionClass)) $this->resourceCollectionClass = iresource('Resources');
        }

        if ($action == 'data' && isset($this->resourceDataCollectionClass) && $this->resourceDataCollectionClass == 'self') {
            $this->resourceDataCollectionClass = null;
        } elseif ($action == 'data' && !isset($this->resourceDataCollectionClass)) {
            $this->resourceDataCollectionClass = iresource($this->class_name(null, true, 1) . "Data");
            if (!class_exists($this->resourceDataCollectionClass)) $this->resourceDataCollectionClass = iresource('ResourcesData');
        }

        if (!isset($this->parentResourceCollectionClass)) {
            if (!isset($this->parentController))
                $this->parentResourceCollectionClass = iresource('Resource');
            else {
                $this->parentResourceCollectionClass = iresource($this->class_name($this->parentController, null, 1));
                if (!class_exists($this->parentResourceCollectionClass)) $this->parentResourceCollectionClass = iresource('Resource');
            }
        }

        if ($action == 'data' && !isset($this->parentResourceDataCollectionClass)) {
            if (!isset($this->parentDataController))
                $this->parentResourceDataCollectionClass = iresource('ResourceData');
            else {
                $this->parentResourceDataCollectionClass = iresource($this->class_name($this->parentController, null, 1) . "Data");
                if (!class_exists($this->parentResourceDataCollectionClass)) $this->parentResourceDataCollectionClass = iresource('ResourceData');
            }
        }
        if ($request->format && is_string($request->format) && in_array($request->format, ['gjson', 'geojson'])) {
            $this->disablePagination = true;
        }
    }

}