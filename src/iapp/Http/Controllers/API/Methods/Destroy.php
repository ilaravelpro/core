<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use Illuminate\Http\Request;

trait Destroy
{
    public function _destroy(Request $request, $arg1, $arg2 = null)
    {
        $args = func_get_args();
        $callback = null;
        if (last($args) instanceof \Closure) {
            $callback = last($args);
            if($arg2 == $callback)
            {
                $arg2 = null;
            }
        }
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        if ($callback) {
            $args = [$request];
            if($parent)
            {
                $args[] = $parent;
            }
            $args[] = $model;
            $result = call_user_func_array($callback, $args);
            if(is_array($result))
            {
                list($parent, $model) = $result;
            }
        } else {
            $model->delete();
        }
        $result = new $this->resourceClass($model);
        if ($parent) {
            $additional[$this->class_name($this->parentController, null, 2)] = new $this->parentResourceCollectionClass($parent);
            $additional['meta'] = [
                'parent' => $this->class_name($this->parentController, null, 2)
            ];
            $result->additional($additional);
        }
        if ($this->clientController && $request->webAccess()) {
            $client = new $this->clientController(...func_get_args());
            $client->webDestroy($request, $result, $arg1, $arg2);
        }
        $this->statusMessage = [":model :action successfully.", ['model' => $this->class_name(), 'action' => "removed"]];
        return $result;
    }
}
