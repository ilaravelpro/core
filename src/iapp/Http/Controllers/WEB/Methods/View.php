<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

trait View
{
    public function view($request)
    {
        if ($request->ajax() && !strstr($request->header('accept'), 'application/json')) {
            self::$result->layouts->mode = $request->header('data-xhr-base') ?: 'xhr';
        } elseif ($request->ajax() && strstr($request->header('accept'), 'application/json')) {
            self::$result->layouts->mode = 'json';
        }

        $as = $request->route()->getAction('as');
        $view = $as;
        $views = method_exists($this, 'views') ? $this->views() : (isset($this->views) ? $this->views : [
            self::$result->module->resource . '.index' => self::$result->module->resource . '.index',
            self::$result->module->resource . '.show' => self::$result->module->resource . '.show',
            self::$result->module->resource . '.create' => self::$result->module->resource . '.create',
            self::$result->module->resource . '.edit' => self::$result->module->resource . '.create'
        ]);
        if (array_key_exists($as, $views)) {
            $view = self::$result->layouts->mode == 'html' ? $views[$as] : (view()->exists($views[$as] . '-'. self::$result->layouts->mode) ? $views[$as] . '-'. self::$result->layouts->mode : $views[$as]);
        }
        $response = response(view()->make($view, (array) self::$result));
        if(self::$result->layouts->mode == 'xhr')
        {
            $content = $response->getContent();
            $data = json_encode(self::$result->global);
            $content = "$data\n$content";
            $response->setContent($content);
        }
        return $response;
    }
}
