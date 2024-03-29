<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Resources extends ResourceCollection
{
    public $format = null;
    public function __construct($resource, $format = null)
    {
        parent::__construct($resource);

        $this->resource = $this->collectResource($resource);
        $this->format = $format ? : request('format');
    }

    public function toArray($request)
    {
        $data = [];
        $controller = $request->route()->getController();
        $resource = isset($controller->resourceClass) && $controller->resourceClass ? $controller->resourceClass :  iresource(class_name($controller, null, 1));
        if (!class_exists($resource)) $resource =  iresource('Resource');
        foreach ($this->resource as $key => $value) {
            switch ($this->format){
                case 'gjson':
                case 'geojson':
                    $data[] = (new $resource($value))->toGeoJson($value);
                    break;
                default:
                    $data[] = new $resource($value);
                    break;
            }
        }
        return $data;
    }
}
