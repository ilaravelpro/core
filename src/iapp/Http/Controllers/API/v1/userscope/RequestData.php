<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\UserScope;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

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
