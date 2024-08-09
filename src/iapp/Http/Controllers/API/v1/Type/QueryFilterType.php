<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 3/1/21, 12:08 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPost\iApp\Http\Controllers\API\v1\Type;


trait QueryFilterType
{
    public function query_filter_type($model, $filter, $params, $current)
    {
        switch ($params->type) {
            case 'parent':
                $parent = $this->model::id($filter->value) ? : $this->model::findByName($filter->value);
                if ($parent && $filter->value){
                    $model->where('parent_id', is_integer($parent) ? $parent : $parent->id);
                    $current['parent'] = $filter->value;
                }
                break;
        }
        return $current;
    }
}
