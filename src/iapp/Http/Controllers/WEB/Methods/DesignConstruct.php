<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Methods;

use Illuminate\Http\Request;

trait DesignConstruct
{
    public function designConstruct(Request $request)
    {
        $as = preg_replace('/^api\./', 'dashboard.', $request->route()->getAction('as'));
        $paths = explode('.', $as);
        $route_resource = preg_replace('/\.[^\.]*$/', '', $as);
        self::$result->module = new \stdClass;
        self::$result->layouts = new \stdClass;
        self::$result->global =  new \stdClass;

        self::$result->module->name = $as;
        if (!isset($this->resource)) {
            $this->resource = join('.', array_splice($paths, 0, -1));
        }

        if (!isset($this->apiResource)) {
            $this->apiResource = 'api.' . preg_replace('/^dashboard\./', '', $this->resource);
        }
        self::$result->module->resource = $this->resource;
        self::$result->module->apiResource = $this->apiResource;
        self::$result->module->action = last($paths);
        self::$result->module->header = _t($as);
        self::$result->module->desc = _t($as . '.desc');
        self::$result->module->icons = [
            'index' => 'fas fa-list-alt',
            'create' => 'fas fa-plus-square',
            'edit' => 'fas fa-edit',
            'show' => 'fas fa-atom'
        ];

        self::$result->global->title = _t('Maravel');
        self::$result->layouts->mode = 'html';
    }
}
