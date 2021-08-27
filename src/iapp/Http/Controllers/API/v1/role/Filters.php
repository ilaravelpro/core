<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 7:42 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Role;


use iLaravel\Core\Vendor\iRole\iRole;
use Illuminate\Support\Facades\Gate;

trait Filters
{
    public function filters($request, $model, $parent = null, $operators = [])
    {
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
            [
                'name' => 'title',
                'title' => _t('title'),
                'type' => 'text'
            ],
            [
                'name' => 'name',
                'title' => _t('name'),
                'type' => 'text'
            ],
        ];
        if (!in_array(auth()->user()->role, ipreference('admins'))) {
            $model->WhereExists(function ($query) {
                $role = $this->model::findByName(auth()->user()->role);
                $query->select(\DB::raw(1))
                    ->from('role_scopes')
                    ->where('role_scopes.role_id', $role->id)
                    ->whereRaw("role_scopes.scope = CONCAT('users.fields.role.', roles.name)")
                    ->where('role_scopes.can', 1)
                ;
            });
        }
        return [$filters, [], $operators];
    }
}
