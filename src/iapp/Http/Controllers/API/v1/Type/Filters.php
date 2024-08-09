<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iPost\iApp\Http\Controllers\API\v1\Type;


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
                'type' => 'text',
            ],
            [
                'name' => 'name',
                'title' => _t('name'),
                'type' => 'text',
            ],
            [
                'name' => 'description',
                'title' => _t('description'),
                'type' => 'text',
            ],
            [
                'name' => 'parent_id',
                'title' => _t('parent'),
                'type' => 'text',
            ],
            [
                'name' => 'grandpa_id',
                'title' => _t('grandpa'),
                'type' => 'text',
            ],
        ];
        return [$filters, [], $operators];
    }
}
