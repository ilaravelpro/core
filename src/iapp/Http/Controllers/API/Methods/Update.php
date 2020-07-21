<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\Methods;

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
            $fields = $this->fillable('update') ?: array_keys($this->rules($request, 'update', ...$args));
            $except = method_exists($this, 'except') ? $this->except($request, 'update', ...$args) : [];
            foreach ($except as $key => $value) {
                $index = array_search($value, $fields);
                if($index !== false)
                {
                    unset($fields[$index]);
                }
            }
            $changed = [];
            $original = [];
            foreach ($fields as $value) {
                if($request->has($value) && $model->$value != $request->$value)
                {
                    $changed[$value] = $request->$value;
                    $original[$value] = $model->$value;
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
            $this->statusMessage = "unchanged";
        }
        return $result;
    }
}
