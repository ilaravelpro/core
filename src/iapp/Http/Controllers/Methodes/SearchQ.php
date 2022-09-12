<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/21/20, 10:50 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;


trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        $id = $parent_id = null;
        if ($id = $this->model::id($q)) $q = $id;
        if (!$id && isset($this->parentModel) && $this->parentModel && $parent_id = $this->parentModel::id($q)) $q = $parent_id;
        $first = false;
        $model->where(function ($query) use ($q, $id, $parent_id, $first) {
            foreach ($this->model::getTableColumns() as $index => $column)
                if ($id && in_array($column, ['id', 'parent']))
                    $query->where(with(new static)->getTable().'.'.$column, $q);
                elseif (!$id && !$parent_id){
                    $query->orWhere(with(new static)->getTable().'.'.$column, 'LIKE', "%$q%");
                }
            return $query;
        });
    }
}
