<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Email extends Eloquent
{
    use Modals\Modal;
    public static $s_prefix = 'ILCE';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $guarded = [];

    protected $hidden = ['model', 'model_id'];
    protected $appends = ['text'];

    public static function findByEmail($model, $model_id, $key, $name, $domain = null)
    {
        if (!$domain && Str::contains($name, '@'))
            list($name, $domain) = explode('@', $name);
        $static = static::class;
        foreach (['model', 'model_id', 'key', 'name', 'domain'] as $index => $datum)
            $static = $index == 'model' ? $static::where($index, $$datum) : $static->where($index, $$datum);
        return $static->first();
    }

    public static function getByModel($model, $id, $key = null)
    {
        $get  = static::where('model', $model)->where('model_id', $id)->where('key', $key);
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
