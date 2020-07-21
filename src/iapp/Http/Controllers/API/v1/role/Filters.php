<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Role;


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
                'name' => 'group',
                'title' => _t('group'),
                'type' => 'text'
            ],
        ];
        $this->requestFilter($request, $model, $parent, $filters, $operators);
        $current = [];
        return [$filters, $current, $operators];
    }
}
