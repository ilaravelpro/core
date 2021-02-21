<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;


trait Filters
{
    public function filters($request, $model, $parent = null)
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
                'name' => 'slug',
                'title' => _t('slug'),
                'type' => 'text',
            ],
            [
                'name' => 'summary',
                'title' => _t('summary'),
                'type' => 'text',
            ],
            [
                'name' => 'type',
                'title' => _t('type'),
                'type' => 'text',
            ],
            [
                'name' => 'order',
                'title' => _t('order'),
                'type' => 'number',
            ],
            [
                'name' => 'published_at',
                'title' => _t('published datetime'),
                'type' => 'datetime',
            ],
        ];
        return [$filters, []];
    }
}
