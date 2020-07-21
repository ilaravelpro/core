<?php


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
                    'can' => 'required|in:1,0',
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
