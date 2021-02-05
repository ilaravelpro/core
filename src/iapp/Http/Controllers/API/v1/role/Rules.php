<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/28/21, 12:23 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Role;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Rules
{
    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
                $rules = [
                    'name' => 'required|alpha',
                    'title' => 'required|min:3',
                    'status' => 'nullable|in:' . join(iconfig('status.roles', iconfig('status.global')), ','),
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }
}
