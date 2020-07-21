<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Status
{
    public function status(Request $request, $type = 'global')
    {
        return ['data' => iconfig("status.$type")];
    }
}
