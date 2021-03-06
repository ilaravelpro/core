<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 10:31 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourcesData extends ResourceCollection
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
        $resource = iresource(class_name($request->route()->getController(), null, 1). "Data");
        if (!class_exists($resource)) $resource = iresource('ResourceData');
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
