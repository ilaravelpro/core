<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/21/20, 10:50 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;


use Illuminate\Support\Collection;

trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        $ids = $parent_ids = null;
        $q = !is_array($q) ? [$q] : $q;
        try {
            $ids = remove_empty(array_map(function ($v) {
                return $this->model::id($v);
            }, $q));
        }catch (\Throwable $exception) {}
        if (!count($ids) && isset($this->parentModel) && $this->parentModel) {
            $parent_ids = remove_empty(array_map(function ($v) {
                return $this->parentModel::id($v);
            }, $q));
        }
        $q = $ids?:$parent_ids?:$q;
        $first = false;
        $table = $this->model::getTableNameDot();
        $model->where(function ($query) use ($q, $ids, $parent_ids, $first, $table) {
            foreach ($this->model::getTableColumns() as $index => $column) {
                if ($ids && in_array($column, ['id', 'parent_id']))
                    $query->whereIn($table . $column, $q);
                elseif (substr($column, -3, 3) === '_id') {
                    if (method_exists($rmodel = $this->model::find(1), $related = str_replace('_id', '', $column))) {
                        $relatedModal = $rmodel->$related();
                        $relatedModal = @$relatedModal->model ?: $relatedModal->getRelated();
                        $items = $relatedModal::getQ($q);
                        if ($items) {
                            foreach ($items as $item) {
                                $query->orWhere(function ($query) use($q, $column, $item, $relatedModal){
                                    $tableNameDot = $relatedModal::getTableNameDot();
                                    if (@$item->kids) {
                                        $query->orWhereIn($tableNameDot . $column, array_merge([$item->id], @$item->kids ? $item->kids->pluck('id')->toArray() : []));
                                    }else $query->whereHas(str_replace('_id', '', $column), function ($query) use ($tableNameDot, $q, $column, $item, $relatedModal) {
                                        foreach ($relatedModal::getTableColumns() as $column2) {
                                            if (in_array($column2, ['id', 'parent_id']) && $item)
                                                $query->where($tableNameDot . $column2, $item->id);
                                            else
                                                $query->orWhere(function ($query) use($tableNameDot, $column2, $q) {
                                                    foreach ($q as $i => $v) {
                                                        $query->orWhere($tableNameDot . $column2, 'LIKE', "%$v%");
                                                    }
                                                });
                                        }
                                    });
                                });
                            }
                        }
                    } else
                        $query->orWhereIn($table . $column, $q);
                } elseif (!$ids && !$parent_ids) {
                    $query->orWhere(function ($query) use($table, $column, $q) {
                        foreach ($q as $i => $v) {
                            $query->{$i == 0 ? 'where' : 'orWhere'}($table . $column, 'LIKE', "%$v%");
                        }
                    });
                }
            }
            return $query;
        });
    }
}
