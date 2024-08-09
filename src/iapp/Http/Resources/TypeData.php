<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/15/20, 1:10 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use iLaravel\Core\iApp\Http\Resources\ResourceData;

class TypeData extends ResourceData
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        try {
            if ($request->has('parent') && $this->parent)
                $data['text'] = $this->text_title;
        }catch (\Throwable $exception) {}
        $data['value'] = $this->name ? : strtolower($this->title);
        if (@$this->parent_id){
            $data['parent_id'] = $this->parent->serial;
        }
        if (@$this->grandpa_id)
            $data['grandpa_id'] = $this->grandpa->serial;
        return $data;
    }
}
