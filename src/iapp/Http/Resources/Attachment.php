<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

class Attachment extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['attachments'] = File::collection($this->resource->attachments);
        return $data;
    }
}
