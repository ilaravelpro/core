<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Rules
{
    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'store':
            case 'update':
                $rules = [
                    'scope' => 'required',
                    'can' => 'required|in:1,0',
                    'role_id' => 'required'
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }
}
