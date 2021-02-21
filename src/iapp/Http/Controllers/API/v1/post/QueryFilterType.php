<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 8:42 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Post;


trait QueryFilterType
{
    public function query_filter_type($model, $filter, $params, $current)
    {
        switch ($params->type) {
            case 'parent':
                if ($parent = $this->model::id($filter->value)){
                    $model->where('parent_id', $parent);
                    $current['parent'] = $filter->value;
                }
                break;
        }
        return $current;
    }
}
