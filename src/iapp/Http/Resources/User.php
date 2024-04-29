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
        if ($this->email) $data['email'] = $this->email->text;
        else unset($data['email']);
        if ($this->mobile) {
            $data['mobile'] = [
                'country' => $this->mobile->country,
                'number' => ($this->mobile->prefix?:''). $this->mobile->number
            ];
        }else unset($data['mobile']);
        unset($data['tokens']);
        unset($data['metas']);
        return $data;
    }

}
