<?php


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
                    'title' => 'required|min:3'
                ];
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }
}
