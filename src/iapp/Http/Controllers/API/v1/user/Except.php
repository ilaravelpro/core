<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\User;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Except
{
    public function except(Request $request, $action)
    {
        switch ($action) {
            case 'store':
            case 'update':
                return ['avatar_file'];
        }
    }
}
