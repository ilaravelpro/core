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
        if (!count($data['mobile'])) unset($data['mobile']);
        if (!count($data['email'])) unset($data['email']);
        if (isset($data['email']) && count($data['email']) && $this->email) $data['email'] = $this->email->text;
        unset($data['avatar_id']);
        unset($data['tokens']);
        return $data;
    }

}
