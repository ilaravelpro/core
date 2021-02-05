<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Fields
{
    public function fields(Request $request, $action)
    {
        $data = [
            'status' => $request->status ?: '',
            'title' => $request->title,
            'content' => $request->post('content'),
        ];
        if($action == 'store')
        {
            $data['type'] = 'attachment';
            $data['creator_id'] = auth()->id();
        }
        return $data;
    }
}
