<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/10/20, 12:49 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Post;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait RequestData
{
    public function requestData(Request $request, $action, &$data)
    {
        if (in_array($action, ['store']))
            $data['creator_id'] = auth()->id();
        if (in_array($action, ['store', 'update']) && isset($data['parent_id'])) {
            $data['parent_id'] = is_array($data['parent_id']) && isset($data['parent_id']['value']) ? $data['parent_id']['value'] : $data['parent_id'];
            $data['parent_id'] = $this->model::id($data['parent_id']);
            unset($data['parent']);
        }
    }
}
