<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/15/20, 1:10 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;


class Type extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if ($this->parent_id){
            $data['parent_id'] = [
                'text' => $this->parent->title,
                'value' => $this->parent->name,
                'id' => $this->parent->serial,
            ];
            if ($this->grandpa_id){
                $data['grandpa_id'] = [
                    'text' => $this->grandpa->title,
                    'value' => $this->grandpa->name,
                    'id' => $this->grandpa->serial,
                ];
            }
        }
        unset($data['creator_id']);
        return $data;
    }
}
