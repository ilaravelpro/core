<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Status
{
    public function status(Request $request, $type = 'global')
    {
        if ($request->type)
            $type = $request->type;
        return ['data' => iconfig("status.$type", iconfig("status.global"))];
    }
}
