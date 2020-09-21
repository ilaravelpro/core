<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

class User extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data = insert_into_array($data, 'family', 'fullname', $this->fullname);
        $data = insert_into_array($data, 'fullname', 'avatar', File::collection($this->avatar));
        unset($data['avatar_id']);
        unset($data['tokens']);
        return $data;
    }

}
