<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 7:42 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Role;


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
        return [$filters, [], $operators];
    }
}
