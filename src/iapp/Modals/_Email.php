<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 11:25 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class _Email extends Eloquent
{
    use Modal;
    public static $s_prefix = 'ILCE';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $guarded = [];

    protected $hidden = ['model', 'model_id'];
    protected $appends = ['text'];

    public static function findByEmail($name, $domain = null, $model = null, $model_id = null, $key = null)
    {
        if (!$domain && Str::contains($name, '@'))
            list($name, $domain) = explode('@', $name);
        $static = static::class;
        foreach (['name', 'domain', 'model', 'model_id', 'key', ] as $datum)
            if ($$datum) $static = $datum == 'name' ? $static::where($datum, $$datum) : $static->where($datum, $$datum);
        return $static->first();
    }

    public static function getByModel($model, $id = null, $key = null)
    {
        $get  = static::where('model', $model);
        if ($id) $get->where('model_id', $id);
        if ($key) $get->where('key', $key);
        return $get->get();
    }

    public function getTextAttribute()
    {
        return $this->name.'@'.$this->domain;
    }

    public function item() {
        if ($this->model){
            $model = imodal($this->model);
            return $model::find($this->model_id);
        }
        return null;
    }
}
