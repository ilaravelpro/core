<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 3:37 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait RequestData
{
    public function requestData(Request $request, $action, &$data)
    {
        if (in_array($action, ['store']))
            $data['creator_id'] = auth()->id();
    }
}
