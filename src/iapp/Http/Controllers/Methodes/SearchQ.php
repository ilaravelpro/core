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
        $table = "";
        try {
            $table = with(new $this->model)->getTable();
            $table .= ".";
        }catch (\Throwable $exception) {
            $table = "";
        }
        $model->where(function ($query) use ($q, $id, $parent_id, $first, $table) {
            foreach ($this->model::getTableColumns() as $index => $column)
                if ($id && in_array($column, ['id', 'parent']))
                    $query->where($table.$column, $q);
                elseif (!$id && !$parent_id){
                    $query->orWhere($table.$column, 'LIKE', "%$q%");
                }
            return $query;
        });
    }
}
