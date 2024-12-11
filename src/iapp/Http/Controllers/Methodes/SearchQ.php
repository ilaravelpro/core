<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/21/20, 10:50 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;


use Illuminate\Database\Eloquent\Relations\Relation;
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
        } catch (\Throwable $exception) {
        }
        if (!count($ids) && isset($this->parentModel) && $this->parentModel) {
            $parent_ids = remove_empty(array_map(function ($v) {
                return $this->parentModel::id($v);
            }, $q));
        }
        $q = $ids ?: $parent_ids ?: $q;
        $first = false;
        $table = $this->model::getTableNameDot();
        $model->where(function ($query) use ($q, $ids, $parent_ids, $first, $table, $model) {
            foreach ($this->model::getTableColumns() as $index => $column) {
                if ($ids && in_array($column, ['id', 'parent_id']))
                    $query->whereIn($table . $column, $q);
                elseif (substr($column, -3, 3) === '_id') {
                    $related = str_replace('_id', '', $column);
                    $rmethod = $related && method_exists(new $this->model, $related) ? new \ReflectionMethod($this->model, $related) : null;
                    $relation = $rmethod ? $rmethod->invoke(new $this->model) : null;
                    if ($relation && $relation instanceof Relation) {
                        $relatedModal = get_class($relation->getRelated());
                        $items = $relatedModal::getQ($q);
                        if ($items)
                            $query->orWhereIn($table . $column, $items->pluck('id')->toArray());
                    } else
                        $query->orWhereIn($table . $column, $q);
                } elseif (!$ids && !$parent_ids) {
                    $query->orWhere(function ($query) use ($table, $column, $q) {
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
