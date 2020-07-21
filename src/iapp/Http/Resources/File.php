<?php

namespace iLaravel\Core\iApp\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
{
    public function toArray($request)
    {
        $file = explode('/', $this->slug);
        return [
            'id' => $this->serial,
            'file_name' => last($file),
            'slug' => $this->slug,
            'url' => $this->url,
            'type' => $this->type,
            'mime' => $this->mime,
            'exec' => $this->exec,
            'created_at' => ($this->created_at instanceof \Carbon\Carbon) ? $this->created_at->unix() : $this->created_at,
            'updated_at' => ($this->updated_at instanceof \Carbon\Carbon) ? $this->updated_at->unix() : $this->updated_at,
        ];
    }
}
