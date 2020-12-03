<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/17/20, 9:16 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class Resource extends JsonResource
{
    public $route_action = null;

    public function toArray($request)
    {
        $role = auth()->check() ? auth()->user()->role : 'guest';
        if (!isset($this->table)) $this->table = class_name($this, true, 2);
        $hidden = iconfig('resources.' . $this->table, []) ? $this->table : 'global';
        $hidden = array_merge(iconfig('resources.' . $hidden . '.hidden.' . $role, []), iconfig('resources.' . $hidden . '.hidden.global', []));
        $data = parent::toArray($request);
        if (isset($data['id']) && isset($this->serial)) {
            $data['id'] = $this->serial;
            $data = insert_into_array($data, 'id', 'id_text', $this->serial_text);
        }
        if (method_exists($this, 'fields'))
            $data = $this->fields($request, $data);
        foreach ($data as $key => $value)
            if (in_array($value, [[]]) || in_array($key, $hidden) || (is_array($value) && count($value) == 0))
                unset($data[$key]);
        if (isset($this->resource->with)) {
            foreach ($this->resource->with as $index => $item) {
                $data[$item] = (new self($this->$item))->toArray($request);
            }
        }
        if (isset($data['id']) && method_exists($request, 'route')) {
            if (!$this->route_action){
                $this->route_action = $request->route()->getAction('as');
                $aAaction = explode('.', $this->route_action);
                array_pop($aAaction);
                $this->route_action = str_replace('api.', '', join('.', $aAaction));
            }
            $actions = [];
            foreach (iconfig('scopes.' . $this->route_action . '.items', []) as $index => $item) {
                $item = str_replace(['edit', 'destroy'], ['update', 'delete'], is_integer($index) ? $item : $index);
                $actions[$item] = Gate::allows($this->route_action.".".$item, [$this->serial]);
            }
            if (count($actions))$data['actions'] = $actions;
        }
        return $data;
    }
}
