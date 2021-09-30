<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 10:46 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ResourceData extends JsonResource
{
    public $_local = null;
    public function __construct($resource)
    {
        $args = func_get_args();
        $this->_local = isset($args[1]) && $args[1] ? $args[1] : null;
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        if (!$this->_local) {
            $args = func_get_args();
            $this->_local = isset($args[1]) && $args[1] ? $args[1] : $request->local;
        }
        if (!isset($this->table)) $this->table = class_name($request->route()->getController(), true, 2);
        $attr = iconfig('resources.' . $this->table, []) ? $this->table : 'global';
        $attr = array_merge(iconfig('resources.' . $attr . '.data', []), ipreference('resources.' . $attr . '.data.global', []));
        $translate = $this->toLocal($this->_local);
        if (isset($attr['text']) && isset($this->{$attr['text']}))
            list($key, $text) = [$attr['text'], $this->{$attr['text']}];
        elseif(isset($this->title))
            list($key, $text) = ['title', $this->title];
        else
            list($key, $text) = ['id', isset($this->serial) ? $this->serial : $this->id];


        $data['text'] = $translate && $key != 'id' && isset($translate[$key]) && $translate[$key] ? $translate[$key] : $text;
        $data['value'] = isset($attr['value']) && isset($this->{$attr['value']}) ? $this->{$attr['value']} :(isset($this->serial) ? $this->serial : $this->id);
        $data['id'] = isset($this->serial) ? $this->serial : $this->id;
        return $data;
    }

    public function toLocal($local)
    {
        return method_exists($this->resource, 'toLocal') ? $this->resource->toLocal($local) : false;
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
