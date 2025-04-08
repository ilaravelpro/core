<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 11:10 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use iLaravel\Core\Vendor\iRole\iRole;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait Index
{
    public $index_time_cached = 60;
    public function _index(Request $request)
    {
        $time = microtime(true);
        list($parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators, $cacheKey) = $this->_queryIndex(...func_get_args());
        $additional = [];
        if ($parent) {
            $parentController = isset($this->parentController) ? $this->parentController : get_class($parent);
            $parent_name = $this->class_name($parentController, null, 2);
            $additional[$parent_name] = new $this->parentResourceCollectionClass($parent);
            $additional['meta'] = [
                'parent' => $parent_name
            ];
        }
        $result = $this->resourceCollectionClass ? new $this->resourceCollectionClass($model) : $this->resourceClass::collection($model);

        if (!isset($additional['meta'])) {
            $additional['meta'] = [];
        }

        if (isset($this->disablePagination))
            $additional['meta']['total'] = $result->count();

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

        $additional['meta']['excute_time'] = round((microtime(true) - $time), 3);
        $result->additional($additional);

        //$result = unserialize($result);
     //   $result->setContent(str_replace('"excute_time":""', '"excute_time":"'.round((microtime(true) - $time), 3).'"', $result->getContent()));
        return $result;
        return  serialize($result->toResponse($request));
        $result = Cache::remember($cacheKey . ':index', now()->addMinutes(@$this->index_time_cached?:60), function () use ($request, $parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators, $cacheKey) {

        });
    }

    public function _queryIndex($request, $parent = null)
    {
        if (method_exists($this, 'queryIndex')) {
            list($parent, $model) = $this->queryIndex($request, $parent);
        } elseif ($parent) {
            $model = $this->model::setEagerLoads([])->select('*');
            $parent = $this->findOrFail($parent, $this->parentController);
        } else {
            $model = $this->model::setEagerLoads([])->select('*');
            $parent = null;
        }

        list($filters, $current_filter, $operators) = [null, null, iconfig('database.operators')];
        if (method_exists($this, 'filters'))
            list($filters, $current_filter) = $this->filters($request, $model, $parent, $operators);
        if (method_exists($this->model, 'filters'))
            list($filters, $current_filter) = $this->model::filters($request, $model, $parent, $operators, $filters, $current_filter);
        list($filters, $current_filter) = $this->requestFilter($request, $model, $parent, $current_filter, $filters, $operators);
        if ($request->q) {
            $this->searchQ($request, $model, $parent);
            $current_filter['q'] = $request->q;
        }
        if ((isset($this->statusFilter) ? $this->statusFilter : true) && ($request->has('statusFilter') ? $request->statusFilter : true) && method_exists($model, 'getModel') && \Schema::hasColumn($model->getModel()->getTable(), 'status')) {
            $statuses = iconfig("status.{$model->getModel()->getTable()}", iconfig("status.global"));
            $status = $request->status ? (in_array($request->status, $statuses) ? $request->status : $statuses[0]) : $statuses[0];
            if ($status) {
                $request->validate([
                    'status' => 'nullable|string',
                ]);
                $prefix_table = method_exists($model, 'getModel') ? ($model->getModel()->getTable() . ".") : '';
                $model->where($prefix_table . 'status', $status);
                $current_filter['status'] = $status;
            }
        }
        if (!isset($this->action)) {
            $this->action = $request->route()->getAction('as');
            $aAaction = explode('.', $this->action);
            array_pop($aAaction);
            $this->action = str_replace('api.', '', join('.', $aAaction));
        }
        if (auth()->check()) {
            $anyByUser = function ($model, $user_names = []) {
                return $model->where(function ($query) use ($model, $user_names) {
                    $idName = null;
                    $table = $model->getModel()->getTable();
                    $table = $table ? "{$table}." : "";
                    if (auth()->user()->role)
                        $user_names[] = auth()->user()->role . "_id";
                    $user_names[] = 'user_id';
                    $user_names[] = 'creator_id';
                    $user_names = array_values(array_filter($user_names, function ($idName) {
                        return in_array($idName, $this->model::getTableColumns());
                    }));
                    if (!count($user_names))
                        return $query;
                    $query->where($table . $user_names[0], auth()->id());
                    array_shift($user_names);
                    foreach ($user_names as $user_name)
                        $query->orWhere($table . $user_name, auth()->id());
                    return $query;
                });
            };
            if ($request->has('is_self') && $request->is_self == 1) {
                $model = $anyByUser($model);
            } elseif (!in_array(auth()->user()->role, ipreference('admins')) && !$parent) {
                $subs = array_filter(iconfig('scopes.' . str_replace('.', '_', $this->action) . '.items.view', []), function ($sub) {
                    return iRole::has(str_replace('.', '_', $this->action) . ".view.$sub");
                });
                if (!count($subs)) $subs = ['anyByUser'];
                foreach ($subs as $sub) {
                    if (function_exists('i_query_index_switch'))
                        $model = i_query_index_switch($sub, $model, $this->action, $request, $anyByUser, $this);
                    elseif ($sub == 'anyByUser')
                        $model = $anyByUser($model);
                }
            }
        }
        if ($parent)  {
            $parentController = isset($this->parentController) ? $this->parentController : get_class($parent);
            $parent_name = $this->class_name($parentController, null, 2);
            if (method_exists($model, 'getModel') && \Schema::hasColumn($model->getModel()->getTable(), $parent_name . '_id')) {
                $model->where($parent_name . '_id', $parent->id);
            }elseif (method_exists($model, 'getModel') && \Schema::hasColumn($model->getModel()->getTable(), 'parent_id')) {
                $model->where( 'parent_id', $parent->id);
            }
        }
        list($model, $order_list, $current_order, $default_order, $cacheKey) = $this->paginate($request, $model, $parent);
        if ($model && method_exists($model, 'appends') && $current_filter) {
            $model->appends($request->all(...array_keys($current_filter)));
        }
        return [$parent, $model, $order_list, $current_order, $default_order, $filters, $current_filter, $operators, $cacheKey];
    }

    public function paginate($request, $model, $parent = null, $order_list = [], $default = [])
    {
        $order_list = $order_list ?: (isset($this->order_list) ? $this->order_list : ['id']);
        $table = $model->getModel()->getTable();
        foreach ($order_list as $key => $value) {
            if (gettype($key) == 'integer') {
                $allowed[$value] = $value;
            } else {
                $allowed[$key] = $value;
            }
        }
        $default = $default ?: (isset($this->order_default) ? $this->order_default : (method_exists($model, 'getModel') ? [[$model->getModel()->getKeyName() => $model->getModel()->getTable() . '.' . $model->getModel()->getKeyName(), 'desc']] : []));
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
                if (isset($custom_sort[$key]) && in_array(strtolower($custom_sort[$key]), ['asc', 'desc', 'random'])) {
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
            if ($value == 'random') {
                $model->orderByRaw('RAND()' );
            } else $model->orderBy($table . '.' . $allowed[$key], $value);
        }
        try {
            $columns = get_class($model->getModel())::getTableColumns();
            if ($request->has_fields && is_array($request->has_fields) && count($request->has_fields)) {
                $has_fields = array_filter($request->has_fields, function ($item) use($columns) {
                    return in_array($item, $columns);
                });
                foreach ($has_fields as $has_field) {
                    $model->whereNotNull("{$table}.{$has_field}")->where("{$table}.{$has_field}", '!=', '\'\'');
                }
            }
        }catch (\Throwable $exception) {}
        $per_page = isset($this->disablePagination) && $this->disablePagination ? ($request->per_page ? $request->per_page : 10) : false;
        $cacheKey = "ilaravel:db:{$model->getModel()->getTable()}:" .  md5($model->toSql() . serialize($model->getBindings())) . '_' . ($per_page == false  ? 'all' : "p_$per_page");
        /*$paginate = Cache::remember("{$cacheKey}:q", now()->addMinutes(@$this->index_time_cached?:60), function () use ($request, $model, $per_page, $order_theory, $default_order) {
            if ($per_page !== false) {
                if (isset($model->emptyModel) && $model->emptyModel === true)
                    $model->limit(0);
                $paginate = $model->paginate($per_page);
                if (join(',', array_keys($order_theory)) != join(',', array_keys($default_order)) || join(',', array_values($order_theory)) != join(',', array_values($default_order))) {
                    $paginate->appends($request->all('order', 'sort'));
                }
            }else $paginate = $model->get();
            return serialize($paginate);
        });
        return [unserialize($paginate), array_keys($allowed), $order_theory, $default_order, $cacheKey];*/
        if ($per_page !== false) {
            if (isset($model->emptyModel) && $model->emptyModel === true)
                $model->limit(0);
            $paginate = $model->paginate($per_page);
            if (join(',', array_keys($order_theory)) != join(',', array_keys($default_order)) || join(',', array_values($order_theory)) != join(',', array_values($default_order))) {
                $paginate->appends($request->all('order', 'sort'));
            }
        }else $paginate = $model->get();
        //$cacheKey = $paginate->cacheKey;
        return [$paginate, array_keys($allowed), $order_theory, $default_order, $cacheKey];
    }
    protected function generateCacheKey($query)
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        return "ilaravel:db:{$this->getModel()->getTable()}:" . md5( $sql . serialize($bindings));
    }
}
