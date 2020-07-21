<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\v1\UserScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait RequestData
{
    public function requestData(Request $request, $action, &$data, $parent)
    {
        if(in_array($action, ['store', 'update']) && isset($data['scope']))
        {
            $data['user_id'] = $parent;
        }
    }
}
