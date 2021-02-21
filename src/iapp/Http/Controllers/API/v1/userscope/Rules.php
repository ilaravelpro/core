<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\UserScope;

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
                    'can' => 'required|boolean',
                    'user_id' => 'required'
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }
}
