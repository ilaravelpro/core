<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Status
{
    public function status(Request $request, $type = 'global')
    {
        return ['data' => iconfig("status.$type")];
    }
}
