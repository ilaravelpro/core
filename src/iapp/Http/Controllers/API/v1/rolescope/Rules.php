<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

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
