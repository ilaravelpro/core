<?php

namespace iLaravel\Core\IApp\Http\Resources;

class Attachment extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['attachments'] = File::collection($this->resource->attachments);
        return $data;
    }
}
