<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 10:46 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ResourceData extends JsonResource
{

    public function toArray($request)
    {
        if (!isset($this->table)) $this->table = class_name($request->route()->getController(), true, 2);
        $attr = iconfig('resources.' . $this->table, []) ? $this->table : 'global';
        $attr = array_merge(iconfig('resources.' . $attr . '.data', []), iconfig('resources.' . $attr . '.data.global', []));
        $data['text'] = isset($attr['text']) && isset($this->{$attr['text']}) ? $this->{$attr['text']} : (isset($this->title) ? $this->title :( isset($this->serial) ? $this->serial : $this->id));
        $data['value'] = isset($attr['value']) && isset($this->{$attr['value']}) ? $this->{$attr['value']} :(isset($this->serial) ? $this->serial : $this->id);
        return $data;
    }
}
