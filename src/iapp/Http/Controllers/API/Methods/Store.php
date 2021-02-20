<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/29/21, 11:56 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use Illuminate\Http\Request;

trait Store
{
    public function _store(Request $request, $parent = null, ...$args)
    {
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

        return $this->additionalStore($request,$this->resultStore($request, $model, $parent), $parent);
    }

    public function resultStore($request, $model, $parent)
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
        $this->statusMessage = $this->class_name() . " created";
        return $result;
    }

    public function store_data(Request $request, $parent = null, ...$args)
    {
        $data = [];
        if (method_exists($this, 'fields')) {
            $data = $this->fields($request, 'store', $parent, ...$args);
        } else {
            $rules = method_exists($this, 'rules') ? $this->rules($request, 'store', $parent, ...$args) : $this->model::getRules($request, 'store', $parent, ...$args);
            $fields = $this->fillable('store') ?: array_keys($rules);
            $except = method_exists($this, 'except') ? $this->except($request, 'store', $parent, ...$args) : [];
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
