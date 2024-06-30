<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 3:52 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use App\RentExtension;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class Resource extends JsonResource
{
    public $route_action = null;
    public $type_action = null;
    public $route_src = null;
    public $c_serial = null;
    public $_local = null;
    public $table = null;
    public function __construct($resource)
    {
        $args = func_get_args();
        $this->_local = isset($args[1]) && $args[1] ? $args[1] : null;
        $resource->unsetRelations();
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        if (!$this->_local) {
            $args = func_get_args();
            $this->_local = isset($args[1]) && $args[1] ? $args[1] : $request->local;
        }
        $role = auth()->check() ? auth()->user()->role : 'guest';
        if (!$this->table && $this->resource && (is_string($this->resource) || $this->resource instanceof Model)) $this->table = method_exists($this->resource, 'getTable') ? $this->resource->getTable() : class_name(request()->route()->getController(), true, 2);
        $hidden = iconfig('resources.' . $this->table, []) ? $this->table : 'global';
        $hidden = array_merge(iconfig('resources.' . $hidden . '.hidden.' . $role, []), iconfig('resources.' . $hidden . '.hidden.global', []));
        $data = parent::toArray($request);
        if (isset($data['id']) && isset($this->serial)) {
            $data['id'] = $this->serial;
            $data = insert_into_array($data, 'id', 'id_text', $this->serial_text);
        }
        $resourceUser = iresourcedata('User')?:ResourceData::class;
        if (isset($this->creator_id) && $this->creator)
            $data['creator_id'] = new $resourceUser($this->creator);

        if (isset($this->user_id) && $this->user)
            $data['user_id'] = new $resourceUser($this->user);

        if (isset($data['meta_data']) && $data['meta_data'] && count($data['meta_data'])) {
            foreach ($data['meta_data'] as $index => $meta_datum) {
                $data = insert_into_array($data, 'status', $index, $meta_datum, false);
            }
            unset($data['meta_data']);
        }
        if ($this && method_exists($this, 'fields'))
            $data = $this->fields($request, $data);
        foreach ($data as $key => $value){
            try {
                if ($value && substr($key, -3, 3) === '_at')
                    $data[$key] = format_datetime($data[$key], isset($this->resource->datetime) ? $this->resource->datetime : [], $key, ipreference('lang'));
                if (in_array($value, [[]]) || in_array($key, $hidden) || (is_array($value) && count($value) == 0))
                    unset($data[$key]);
            }catch (\Throwable $exception) {}
        }
        $has_actions = false;
        if (isset($data['id']) && method_exists($request, 'route') && $request->route() && method_exists($request->route(), 'getController') && $request->route()->getController()->model == imodal(class_name($this->resource))) {
            if (!$this->route_src){
                $this->route_src = $request->route()->getAction('as');
                $aAaction = explode('.', $this->route_src);
                $this->route_action = end($aAaction);
                array_pop($aAaction);
                $this->route_src = str_replace('api.', '', join('.', $aAaction));
                $has_actions = true;
            }
            $this->type_action = in_array($this->route_action, ['data', 'index']) ? 'index' : 'single';
        }else
            $has_actions = false;

        $with_resource = isset($this->resource->with_resource) ? $this->resource->with_resource : [];
        $with_resource = isset($this->resource->{"with_resource_" . $this->route_action}) ? array_merge($this->resource->{"with_resource_" . $this->route_action}, $with_resource) : $with_resource;
        if ($this->type_action) $with_resource = isset($this->resource->{"with_resource_" . $this->type_action}) ? array_merge($this->resource->{"with_resource_" . $this->type_action}, $with_resource) : $with_resource;
        foreach ($with_resource as $index => $item) {
            $name = is_int($index) ? $item : $index;
            $resourceName = is_int($index) ? null : $item;
            if ($this->$name) {
                $resourceModal = iresource($resourceName?:class_name($this->$name))?:static::class;
                $data[$name] = $this->$name instanceof Collection ? $resourceModal::collection($this->$name) : new $resourceModal($this->$name);
            }
        }
        if (isset($this->resource->with_resource_smart)) {
            foreach ($this->resource->with_resource_smart as $index => $item) {
                $name = is_int($index) ? $item : $index;
                if ($this->type_action == "index") {
                    if ($this->$name) $data["{$name}_count"] = $this->$name()->count();
                }else {
                    $resourceName = is_int($index) ? null : $item;
                    if ($this->$name) {
                        $resourceModal = iresource($resourceName?:class_name($this->$name))?:static::class;
                        $data[$name] = $this->$name instanceof Collection ? $resourceModal::collection($this->$name) : new $resourceModal($this->$name);
                    }
                }
            }
        }
        $with_resource_data = isset($this->resource->with_resource_data) ? $this->resource->with_resource_data : [];
        $with_resource_data = isset($this->resource->{"with_resource_data_" . $this->route_action}) ? array_merge($this->resource->{"with_resource_data_" . $this->route_action}, $with_resource_data) : $with_resource_data;
        if ($this->type_action) $with_resource_data = isset($this->resource->{"with_resource_data_" . $this->type_action}) ? array_merge($this->resource->{"with_resource_data_" . $this->type_action}, $with_resource_data) : $with_resource_data;
        foreach ($with_resource_data as $index => $item) {
            $name = is_int($index) ? $item : $index;
            $resourceName = is_int($index) ? null : $item;
            if ($this->$name) {
                $resourceModal = iresourcedata($resourceName?:class_name($this->$name))?:iresourcedata('Resource');
                $data[$name . '_id'] = $this->$name instanceof Collection ? $resourceModal::collection($this->$name) : new $resourceModal($this->$name);
            }
        }
        $with_resource_data_normal = isset($this->resource->with_resource_data_normal) ? $this->resource->with_resource_data_normal : [];
        $with_resource_data_normal = isset($this->resource->{"with_resource_data_normal_" . $this->route_action}) ? array_merge($this->resource->{"with_resource_data_normal_" . $this->route_action}, $with_resource_data_normal) : $with_resource_data_normal;
        if ($this->type_action) $with_resource_data_normal = isset($this->resource->{"with_resource_data_normal_" . $this->type_action}) ? array_merge($this->resource->{"with_resource_data_normal_" . $this->type_action}, $with_resource_data_normal) : $with_resource_data_normal;
        foreach ($with_resource_data_normal as $index => $item) {
            $name = is_int($index) ? $item : $index;
            $resourceName = is_int($index) ? null : $item;
            if ($this->$name) {
                $resourceModal = iresource($resourceName?:class_name($this->$name))?:iresource('Resource');
                $data[$name . '_id'] = $this->$name instanceof Collection ? $resourceModal::collection($this->$name) : new $resourceModal($this->$name);
            }
        }
        if (isset($this->resource->files))
            foreach ($this->resource->files as $item) {
                if (isset($data[$item.'_id'])) {
                    $file = $this->$item ?: $this->resource->getFile($item);
                    if ($file) $data = insert_into_array($data, $item.'_id', $item, File::collection($file));
                    unset($data[$item.'_id']);
                }
            }

        if (auth()->check() &&$has_actions) {
            $actions = [];
            foreach (iconfig('scopes.' . $this->route_src . '.items', []) as $index => $item) {
                $item = str_replace(['edit', 'destroy'], ['update', 'delete'], is_integer($index) ? $item : $index);
                $actions[$item] = Gate::allows($this->route_src.".".$item, [$this->c_serial ? : $this->serial]);
            }
            if (count($actions))$data['actions'] = $actions;
        }
        $translate = $this->toLocal($this->_local);
        if ($translate) $data = array_merge($data, $translate);

        return $data;
    }

    public function toLocal($local)
    {
        return $this->resource && (is_string($this->resource) || is_object($this->resource)) && method_exists($this->resource, 'toLocal') ? $this->resource->toLocal($local) : false;
    }

    public static function collection($resource)
    {
        $args = func_get_args();
        $local = isset($args[1]) && $args[1] ? $args[1] : null;
        return tap(new AnonymousResourceCollection($resource, static::class), function ($collection) use($local) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([], $local))->preserveKeys === true;
            }
        });
    }
}
