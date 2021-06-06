<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 2:57 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;


trait Filters
{
    public function filters($request, $model, $parent = null, $operators = [])
    {
        $roles = imodal('Role');
        $roles = $roles::select('title as text', 'name as value')->get()->toArray();
        if (in_array(auth()->user()->role, ipreference('admins', ['admin'])))
            $roles = array_merge([
                ['text' => _t('admin'),
                    'value' => 'admin'],
            ], $roles);
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
            [
                'name' => 'role',
                'title' => _t('role'),
                'type' => 'select',
                'items' => $roles
            ],
            [
                'name' => 'gender',
                'title' => _t('gender'),
                'type' => 'select',
                'items' => [
                    [
                        'text' => _t('male'),
                        'value' => 'male'
                    ],
                    [
                        'text' => _t('female'),
                        'value' => 'female'
                    ]
                ]
            ],
            [
                'name' => 'username',
                'title' => _t('username'),
                'type' => 'text'
            ],
        ];
        return [$filters, [], $operators];
    }
}
