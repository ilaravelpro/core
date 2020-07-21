<?php

namespace iLaravel\Core\iApp\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        $role = auth()->check() ? auth()->user()->role : 'guest';
        $hidden = iconfig('resources.'.$this->table, []) ? $this->table : 'global';
        $hidden = array_merge(iconfig('resources.'.$hidden.'.hidden.'.$role, []), iconfig('resources.'.$hidden.'.hidden.global', []));
        $data = parent::toArray($request);
        if (isset($data['id']) && isset($this->serial)){
            $data['id'] =  $this->serial;
            $data = insert_into_array($data, 'id', 'id_text', $this->serial_text);
        }
        if (method_exists($this,'fields'))
            $data = $this->fields($request, $data);
        foreach ($data as $key => $value)
            if (in_array($value, [[]]) || in_array($key, $hidden)|| (is_array($value) && count($value) == 0))
                unset($data[$key]);
        return $data;
    }
}
