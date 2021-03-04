<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 3/5/21, 12:19 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;


class Phone extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if ($this->model && $model = imodal($this->model))
            $this->model_id = $model::serial($this->model_id);
        $data['number'] = (isset($data['prefix']) ? $data['prefix']:''). $data['number'];
        if (isset($data['pivot']))
            unset($data['actions']);
        unset($data['pivot']);
        return $data;
    }
}
