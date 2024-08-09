<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/21/21, 6:07 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPost\iApp\Http\Controllers\API\v1\Type;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;


trait RequestData
{
    public function requestData(Request $request, $action, &$data)
    {

        if (in_array($action, ['store', 'update']) && isset($data['parent_id'])) {
            $data['parent_id'] = is_array($data['parent_id']) && isset($data['parent_id']['value']) ? $data['parent_id']['value'] : $data['parent_id'];
            $data['parent_id'] = $this->model::findByName($data['parent_id'])->id;
            unset($data['parent']);
        }
    }
}
