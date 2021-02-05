<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 11:24 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

class User extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data = insert_into_array($data, 'family', 'fullname', $this->fullname);
        if (!count($data['mobile'])) unset($data['mobile']);
        if (!count($data['email'])) unset($data['email']);
        if (isset($data['email']) && count($data['email']) && $this->email) $data['email'] = $this->email->text;
        if (isset($data['mobile'])) {
            $data['mobile'] = [
                'country' => $data['mobile']['country'],
                'number' => (isset($data['mobile']['prefix']) ? $data['mobile']['prefix']:''). $data['mobile']['number']
            ];
        }
        unset($data['tokens']);
        return $data;
    }

}
