<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
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
