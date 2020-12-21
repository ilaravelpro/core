<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/10/20, 12:49 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;


trait Filters
{
    public function filters($request, $model, $parent = null, $operators = [])
    {
        $user = auth()->user();
        $types = imodal('Role');
        $types = $types::select('title as text, name as value')->get()->toArray();
        $types = [
            'text' => 'Admin',
            'value' => 'admin'
        ];
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
            [
                'name' => 'status',
                'title' => _t('status'),
                'type' => 'select',
                'items' => iconfig('status.users', ['awaiting', 'active', 'disable'])
            ],
            [
                'name' => 'role',
                'title' => _t('role'),
                'type' => 'select',
                'items' => $types
            ],
            [
                'name' => 'gender',
                'title' => _t('gender'),
                'type' => 'select',
                'items' => ['male', 'female']
            ],
            [
                'name' => 'username',
                'title' => _t('username'),
                'type' => 'text'
            ],
        ];
        $current = [];
        $this->requestFilter($request, $model, $parent, $filters, $operators);
        if ($request->q) {
            $this->searchQ($request, $model, $parent);
            $current['q'] = $request->q;
        }
        return [$filters, $current, $operators];
    }
}
