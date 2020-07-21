<?php

namespace iLaravel\Core\IApp\Http\Resources;

class User extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data = insert_into_array($data, 'family', 'fullname', $this->fullname);
        $data['mobile'] = convMobile($this->mobile);
        $data = insert_into_array($data, 'fullname', 'avatar', File::collection($this->avatar));
        unset($data['avatar_id']);
        return $data;
    }

}
