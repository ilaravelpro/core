<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
{
    public function toArray($request)
    {
        $file = explode('/', $this->slug);
        $model = imodal('Attachment');
        return [
            'id' => $this->serial,
            'file_name' => last($file),
            'slug' => $this->slug,
            'attachment' => $model::serial($this->post_id),
            'url' => $this->url,
            'type' => $this->type,
            'mime' => $this->mime,
            'exec' => $this->exec,
            'created_at' => ($this->created_at instanceof \Carbon\Carbon) ? $this->created_at->unix() : $this->created_at,
            'updated_at' => ($this->updated_at instanceof \Carbon\Carbon) ? $this->updated_at->unix() : $this->updated_at,
        ];
    }
}
