<?php

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
