<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Rules
{
    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
                $rules = [
                    'file' => 'required',
                    'status' => 'nullable|in:draft,publish,disable',
                    'title' => 'nullable',
                    'content' => 'nullable',
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }
}
