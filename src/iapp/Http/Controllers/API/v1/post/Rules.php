<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Post;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Rules
{
    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
                $rules = [
                    'title' => 'required',
                    'content' => 'required',
                    'summary' => 'nullable|max:400',
                    'slug' => 'require',
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }
}
