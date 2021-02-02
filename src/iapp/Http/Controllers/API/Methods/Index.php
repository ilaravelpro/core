<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/17/20, 9:29 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use iLaravel\Core\Vendor\iRole\iRole;
use Illuminate\Http\Request;

trait Index
{
    public function _index(Request $request)
    {
        list($parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators) = $this->_queryIndex(...func_get_args());
        $result = $this->resourceCollectionClass ? new $this->resourceCollectionClass($model) : $this->resourceClass::collection($model);
        $additional = [];
        if ($parent) {
            $parentController = isset($this->parentController) ? $this->parentController : get_class($parent);
            $additional[$this->class_name($parentController, null, 2)] = new $this->parentResourceCollectionClass($parent);
            $additional['meta'] = [
                'parent' => $this->class_name($parentController, null, 2)
            ];
        }

        if (!isset($additional['meta'])) {
            $additional['meta'] = [];
        }
        $additional['meta']['orders'] = [
            'allowed' => $order_list ?: [],
            'current' => $current_order ?: [],
            'default' => $default_order,
        ];
        $additional['meta']['filters'] = [
            'allowed' => $filters ?: [],
            'current' => $current_filter ?: [],
            'operators' => $operators,
        ];

        $result->additional($additional);
        return $result;
    }

    public function _queryIndex($request, $parent = null)
    {
        if (method_exists($this, 'queryIndex')) {
            list($parent, $model) = $this->queryIndex($request, $parent);
        } elseif ($parent) {
            $model = $this->model::select('*');
            $parent = $this->findOrFail($parent, $this->parentController);
        } else {
            $model = $this->model::select('*');
            $parent = null;
        }
        list($filters, $current_filter, $operators) = [null, null, iconfig('database.operators')];
        if (method_exists($this, 'filters'))
            list($filters, $current_filter) = $this->filters($request, $model, $parent, $operators);
        list($filters, $current_filter) = $this->requestFilter($request, $model, $parent, $current_filter, $filters, $operators);
        if ($request->q) {
            $this->searchQ($request, $model, $parent);
            $current['q'] = $request->q;
        }
        if (\Schema::hasColumn($model->getModel()->getTable(), 'status')){
            $statuses = iconfig("status.{$model->getModel()->getTable()}", iconfig("status.global"));
            $status = $request->status ? (in_array($request->status, $statuses) ? $request->status : $statuses[0]) : $statuses[0];
            if ($status) {
                $model->where('status', $status)->orWhere('status', null);
                $current_filter['status'] = $status;
            }
        }
        if (auth()->check() && !in_array(auth()->user()->role, ipreference('admins')) && !$parent) {
            if (!isset($this->action)) {
                $this->action = $request->route()->getAction('as');
                $aAaction = explode('.', $this->action);
                array_pop($aAaction);
                $this->action = str_replace('api.', '', join('.', $aAaction));
            }
            $anyByUser = function ($model) {
                return $model->where(function ($query) use ($model) {
                    $idName = null;
                    if (auth()->user()->role && \Schema::hasColumn($model->getModel()->getTable(), auth()->user()->role . '_id'))
                        $idName = auth()->user()->role."_id";
                    elseif (\Schema::hasColumn($model->getModel()->getTable(), 'user_id'))
                        $idName = 'user_id';
                    elseif (\Schema::hasColumn($model->getModel()->getTable(), 'creator_id'))
                        $idName = 'creator_id';
                    if ($idName)
                        $query->where($idName, auth()->id());
                    return $query;
                });
            };
            $subs = array_filter(iconfig('scopes.' . $this->action . '.items.view', []), function ($sub) {
                return iRole::has("$this->action.view.$sub");
            });
            foreach ($subs as $sub) {
                if (function_exists('i_query_index_switch'))
                    $model = i_query_index_switch($sub, $model,$this->action , $request, $anyByUser);
                elseif ($sub == 'anyByUser') {
                    $model = $anyByUser($model);
                }
            }
        }

        list($model, $order_list, $current_order, $default_order) = $this->paginate($request, $model, $parent);
        if ($current_filter) {
            $model->appends($request->all(...array_keys($current_filter)));
        }

        return [$parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators];
    }

    public function paginate($request, $model, $parent = null, $order_list = [], $default = [])
    {
        $order_list = $order_list ?: (isset($this->order_list) ? $this->order_list : ['id']);
        foreach ($order_list as $key => $value) {
            if (gettype($key) == 'integer') {
                $allowed[$value] = $value;
            } else {
                $allowed[$key] = $value;
            }
        }

        $default = $default ?: (isset($this->order_default) ? $this->order_default : method_exists($model, 'getModel') ? [[$model->getModel()->getKeyName() => $model->getModel()->getTable() . '.' . $model->getModel()->getKeyName(), 'desc']] : []);
        $default_order = [];
        foreach ($default as $key => $value) {
            if (gettype(key($value)) == 'integer') {
                $default_order[current($value)] = next($value);
            } else {
                $default_order[key($value)] = $value[0];
            }
        }
        foreach ($default_order as $key => $value) {
            $value = trim($value);
            $key = trim($key);
            if (!isset($allowed[$key])) {
                $allowed[$key] = $key;
            }
        }
        $order_theory = [];
        if ($request->order) {
            $custom_order = is_array($request->order) ? $request->order : [$request->order];
            $custom_sort = is_array($request->sort) ? $request->sort : [$request->sort];
            foreach ($custom_order as $key => $value) {
                if (!isset($allowed[$value])) continue;
                if (isset($custom_sort[$key]) && in_array(strtolower($custom_sort[$key]), ['asc', 'desc'])) {
                    $order_theory[$value] = $custom_sort[$key];
                } else {
                    $order_theory[$value] = 'desc';
                }
            }
        }
        if (empty($order_theory)) {
            $order_theory = $default_order;
        }
        if (!$model instanceof \Illuminate\Database\Eloquent\Builder) {
            return [$model, $allowed, $order_theory, $default_order];
        }
        foreach ($order_theory as $key => $value) {
            $model->orderBy($allowed[$key], $value);
        }
        if (isset($this->disablePagination)) {
            $paginate = $model->get();
        } else {
            if (isset($model->emptyModel) && $model->emptyModel === true) {
                $model->limit(0);
            }
            $paginate = $model->paginate($request->per_page ? $request->per_page : 10);
            if (join(',', array_keys($order_theory)) != join(',', array_keys($default_order)) || join(',', array_values($order_theory)) != join(',', array_values($default_order))) {
                $paginate->appends($request->all('order', 'sort'));
            }
        }
        return [$paginate, array_keys($allowed), $order_theory, $default_order];
    }
}
