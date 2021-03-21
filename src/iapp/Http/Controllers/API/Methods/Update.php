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
    public function _update(Request $request, $arg1, $arg2 = null, $arg3 = null)
    {
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
            $fields = $this->fields($request, 'update', $parent, ...$args);
            $except = method_exists($this, 'except') ? $this->except($request, 'update', ...$args) : [];
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
            $rules = method_exists($this, 'rules') ? $this->rules($request, 'update', ...$args2) : $this->model::getRules($request, 'update', ...$args2);
            $fields = $this->fillable('update') ?: array_keys($rules);
            $except = method_exists($this, 'except') ? $this->except($request, 'update', ...$args2) : [];
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
        return $result;
    }

}
