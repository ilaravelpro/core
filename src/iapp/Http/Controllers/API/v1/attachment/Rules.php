<?php

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
