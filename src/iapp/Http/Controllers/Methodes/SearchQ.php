<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/6/20, 12:03 AM
 * Copyright (c) 2020. Powered by iamir.net
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
        $model->where(function ($query) use ($q, $id, $parent_id) {
            foreach ($this->model::getTableColumns() as $column)
                if (in_array($column, ['id', 'parent']))
                    $query->where($column, $q);
                elseif (!$id && !$parent_id)
                    $query->orWhere($column, 'LIKE', "%$q%");
            return $query;
        });
    }
}
