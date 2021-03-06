<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 3:52 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class Resource extends JsonResource
{
    public $route_action = null;
    public $route_src = null;
    public $c_serial = null;

    public function toArray($request)
    {
        $role = auth()->check() ? auth()->user()->role : 'guest';
        if (!isset($this->table)) $this->table = class_name(request()->route()->getController(), true, 2);
        $hidden = iconfig('resources.' . $this->table, []) ? $this->table : 'global';
        $hidden = array_merge(iconfig('resources.' . $hidden . '.hidden.' . $role, []), iconfig('resources.' . $hidden . '.hidden.global', []));
        $data = parent::toArray($request);
        if (isset($data['id']) && isset($this->serial)) {
            $data['id'] = $this->serial;
            $data = insert_into_array($data, 'id', 'id_text', $this->serial_text);
        }
        if (method_exists($this, 'fields'))
            $data = $this->fields($request, $data);
        foreach ($data as $key => $value){
            if (substr($key, -3, 3) === '_at' && ipreference('lang') == 'fa' && $data[$key])
                $data[$key] = jdate($data[$key])->format('Y-m-d H:i:s');
            if (in_array($value, [[]]) || in_array($key, $hidden) || (is_array($value) && count($value) == 0))
                unset($data[$key]);
        }
        if (isset($this->resource->with)) {
            foreach ($this->resource->with as $index => $item) {
                $data[$item] = (new self($this->$item))->toArray($request);
            }
        }
        if (isset($this->resource->files))
            foreach ($this->resource->files as $item) {
                if ($this->$item) $data = insert_into_array($data, $item.'_id', $item, File::collection($this->$item));
                unset($data[$item.'_id']);
            }
        if (isset($data['id']) && method_exists($request, 'route') && !request()->has('no_actions') && $request->route()) {
            if (!$this->route_src){
                $this->route_src = $request->route()->getAction('as');
                $aAaction = explode('.', $this->route_src);
                $this->route_action = end($aAaction);
                array_pop($aAaction);
                $this->route_src = str_replace('api.', '', join('.', $aAaction));
            }
            $actions = [];
            foreach (iconfig('scopes.' . $this->route_src . '.items', []) as $index => $item) {
                $item = str_replace(['edit', 'destroy'], ['update', 'delete'], is_integer($index) ? $item : $index);
                $actions[$item] = Gate::allows($this->route_src.".".$item, [$this->c_serial ? : $this->serial]);
            }
            if (count($actions))$data['actions'] = $actions;
        }
        return $data;
    }

}
