<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 11:24 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

class Role extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        unset($data['creator_id']);
        return $data;
    }

}
