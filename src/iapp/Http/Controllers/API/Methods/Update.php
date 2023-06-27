<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/1/21, 3:53 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use Illuminate\Http\Request;

trait Update
{
    public function _update(Request $request, $arg1, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        if (method_exists($this, 'before_update'))
            $this->before_update($request, $arg1, $arg2, $arg3, $arg4);
        if (method_exists($this, 'before_save'))
            $this->before_save($request, $arg1, $arg2, $arg3, $arg4);
        if (!$arg4) $arg4 = 'update';
        $callback = null;
        if($arg2 instanceof \Closure)
        {
            $callback = $arg2;
            $arg2 = null;
        }
        if ($arg3 instanceof \Closure) {
            $callback = $arg3;
        }
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        $args = [$model];
        if($parent)
        {
            array_unshift($args, $parent);
        }
        if (method_exists($this, 'fields')) {
            $fields = $this->fields($request, $arg4, $parent, ...$args);
            $except = method_exists($this, 'except') ? $this->except($request, $arg4, ...$args) : [];
            if ($model && isset($model->files) && is_array($model->files) && count($model->files)) $except = array_merge($except, array_map(function ($v) {
                return "{$v}_file";
            }, $model->files));
            foreach ($except as $value) {
                if (isset($fields[$value])) {
                    unset($fields[$value]);
                }
            }
            $changed = [];
            $original = [];
            foreach ($fields as $key => $value) {
                if ($request->has($key) && $model->$key != $fields[$key]) {
                    $changed[$key] = $value;
                    $original[$key] = $model->$key;
                }
            }
        }
        else
        {
            $args2 = $parent ? array_merge([$parent], $args): $args;
            $rules = method_exists($this, 'rules') ? $this->rules($request, $arg4, ...$args2) : $this->model::getRules($request, $arg4, ...$args2);
            $fields = $this->fillable($arg4) ?: array_keys($rules);
            $except = method_exists($this, 'except') ? $this->except($request, $arg4, ...$args2) : [];
            if ($model && isset($model->files) && is_array($model->files) && count($model->files)) $except = array_merge($except, array_map(function ($v) {
                return "{$v}_file";
            }, $model->files));
            $changed = [];
            $original = [];
            $requestArray = $request->toArray();
            $fields = $this->handelFields($except, $fields, $requestArray);
            foreach ($fields as $value) {
                if(_has_key($request->toArray(), $value) && (_get_value($request->toArray(), $value) !== null || _get_value($model->toArray(), $value) != _get_value($request->toArray(), $value)))
                {
                    $changed = _set_value($changed, $value, _get_value($requestArray, $value));
                    $original = _set_value($original, $value, _get_value($model->toArray(), $value));
                }
            }

        }
        if($callback)
        {
            array_push($args, $changed);
            array_unshift($args, $request);
            $func_changed = call_user_func_array($callback, $args);
            if(is_array($func_changed))
            {
                $original = $func_changed;
            }
        }
        else
        {
            $model->update($changed);
        }
        if (method_exists($this, 'after_update'))
            $this->after_update($request, $model, $parent);
        if (method_exists($this, 'after_save'))
            $this->after_save($request, $model, $parent);
        $result = new $this->resourceClass($model);
        $result->additional([
            'changed' => $original,
        ]);
        if ($parent) {
            $parentController = isset($this->parentController) ? $this->parentController : get_class($parent);
            $additional[$this->class_name($parentController, null, 2)] = new $this->parentResourceCollectionClass($parent::find($parent->id));
            $additional['meta'] = [
                'parent' => $this->class_name($parentController, null, 2)
            ];
            $result->additional($additional);
        }

        if ($this->clientController && $request->webAccess()) {
            $client = new $this->clientController(...func_get_args());
            $client->webUpdate($request, $result);
        }
        if(!empty($original))
        {
            $this->statusMessage = $this->class_name() . " changed";
        }
        else
        {
            $this->statusMessage = "changed";
        }
        if (method_exists($this, 'after_updated'))
            $this->after_updated($request, $model, $parent, $result);
        if (method_exists($this, 'after_saved'))
            $this->after_saved($request, $model, $parent, $result);
        return $result;
    }

}
