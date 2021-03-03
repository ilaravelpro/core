<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 8:42 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Post;


trait RequestFilter
{
    public function requestFilter($request, $model, $parent, $current, $filters, $operators)
    {
        list($filters, $current)  = parent::requestFilter($request, $model, $parent, $current, $filters, $operators);
        $parentSet = $request->has('parent') ? (boolean) $request->parent : true;
        if ((!isset($current['parent']) || !$current['parent']) && $parentSet){
            $model->where('parent_id', null)->orWhere('parent_id','<=', 0);
        }
        return [$filters, $current];
    }
}
