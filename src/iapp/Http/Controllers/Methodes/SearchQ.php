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
        $table = $this->model::getTableNameDot();
        $model->where(function ($query) use ($q, $id, $parent_id, $first, $table) {
            foreach ($this->model::getTableColumns() as $index => $column) {
                if ($id && in_array($column, ['id', 'parent']))
                    $query->where($table . $column, $q);
                elseif (substr($column, -3, 3) === '_id') {
                    if (method_exists($rmodel = (new ($this->model)), $related = str_replace('_id', '', $column))) {
                        $relatedModal = $rmodel->$related();
                        $relatedModal = @$relatedModal->model ?: $relatedModal->getRelated();
                        $query->orWhereHas(str_replace('_id', '', $column), function ($query) use ($q, $column, $relatedModal) {
                            $tableNameDot = $relatedModal::getTableNameDot();
                            foreach ($relatedModal::getTableColumns() as $column2) {
                                if (in_array($column2, ['id', 'parent_id']))
                                    $query->where($tableNameDot . $column2, $q);
                                else
                                    $query->orWhere($tableNameDot . $column2, 'LIKE', "%$q%");
                            }
                        });
                    } else
                        $query->orWhere($table . $column, $q);
                } elseif (!$id && !$parent_id) {
                    $query->orWhere($table . $column, 'LIKE', "%$q%");
                }
            }
            return $query;
        });
    }
}
