<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/29/21, 11:56 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Store
{
    public function _store(Request $request, $parent = null, ...$args)
    {
        if (method_exists($this, 'before_store'))
            $this->before_store($request, $parent, ...$args);
        if (method_exists($this, 'before_save'))
            $this->before_save($request, $parent, ...$args);
        $callback = null;
        if (last($args) instanceof \Closure) {
            $callback = last($args);
            array_pop($args);
        } elseif ($parent instanceof \Closure) {
            $callback = $parent;
            $parent = null;
        }
        if($parent)
        {
            $parent = $this->findOrFail($parent, isset($this->parentController) ? $this->parentController : get_class($parent));
        }
        if ($callback) {
            $args = [$request];
            if($parent)
            {
                $args[] = $parent;
            }
            $args[] = $this->store_data($request, $parent, ...$args);
            $model = call_user_func_array($callback, $args);
        } else {
            $model = $this->model::create($this->store_data($request, $parent, ...$args));
        }
        if (method_exists($this, 'after_store'))
            $this->after_store($request, $model, $parent);
        if (method_exists($this, 'after_save'))
            $this->after_save($request, $model, $parent);
        if (method_exists($model, 'additionalUpdate'))
            $model->additionalUpdate($request, null, $parent);
        if (method_exists($model, 'save_locals'))
            $this->save_locals($request, $model);
        $result = $this->additionalStore($request,$this->resultStore($request, $model, $parent), $parent);
        if (method_exists($this, 'after_stored'))
            $this->after_stored($request, $model, $parent, $result);
        if (method_exists($this, 'after_saved'))
            $this->after_saved($request, $model, $parent, $result);
        return $result;
    }

    public function resultStore($request, $model, $parent = null)
    {
        $model = get_class($model)::findOrFail($model->id);
        return new $this->resourceClass($model);
    }

    public function additionalStore($request, $result, $parent)
    {
        if ($parent) {
            $parentController = isset($this->parentController) ? $this->parentController : get_class($parent);
            $additional[$this->class_name($parentController, null, 2)] = new $this->parentResourceCollectionClass($parent::find($parent->id));
            $additional['meta'] = [
                'parent' => $this->class_name($parentController, null, 2)
            ];
            $result->additional($additional);
        }
        if (isset($this->clientController) && $request->webAccess()) {
            $client = new $this->clientController(...func_get_args());
            $client->webStore($request, $result);
        }
        $this->statusMessage = [":model :action successfully.", ['model' => _t($this->class_name()), 'action' => _t("created")]];
        return $result;
    }

    public function store_data(Request $request, $parent = null, ...$args)
    {
        $data = [];
        if (method_exists($this, 'fields')) {
            $data = $this->fields($request, 'store', $parent, ...$args);
        } else {
            $args2 = $parent ? array_merge([$parent], $args): $args;
            $rules = method_exists($this, 'rules') ? $this->rules($request, 'store', ...$args2) : $this->model::getRules($request, 'store', ...$args2);
            $fields = $this->fillable('store') ?: array_keys($rules);
            $except = method_exists($this, 'except') ? $this->except($request, 'store', ...$args2) : [];
            $model = new $this->model;
            if ($model && isset($model->files) && is_array($model->files) && count($model->files)) $except = array_merge($except, array_map(function ($v) {
                return "{$v}_file";
            }, $model->files));
            $requestArray = $request->toArray();
            $fields = $this->handelFields($except, $fields, $requestArray);
            foreach ($fields as $value) {
                if (_has_key($request->toArray(), $value)) {
                    $data = _set_value($data, $value, _get_value($request->toArray(), $value));
                }
            }
        }
        return $data;
    }
}
