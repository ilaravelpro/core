<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/28/21, 12:09 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Status
{
    public function status(Request $request, $type = 'global')
    {
        if ($request->type)
            $type = $request->type;
        return ['data' => array_map(function ($status) {
            return [
                'text' => _t($status),
                'value' => $status,
            ];
        }, iconfig("status.$type", iconfig("status.global")))];
    }
}
