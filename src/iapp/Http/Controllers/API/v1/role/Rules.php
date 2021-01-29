<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
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
