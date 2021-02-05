<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 5:22 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use iLaravel\Core\Vendor\Validations\iPhone;
use Illuminate\Database\Eloquent\Model as Eloquent;

class _Phone extends Eloquent
{
    use Modal;
    public static $s_prefix = 'ILCP';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $guarded = [];

    protected $hidden = ['model', 'model_id'];
    protected $appends = ['text'];

    public static function findByMobile($mobile, $model = null, $id = null, $key = null)
    {
        $mobile = iPhone::parse($mobile);
        if (!$mobile) return false;
        $find  = static::where('type', 'mobile')->where(function ($query) use ($mobile) {
            foreach ($mobile as $index => $item){
                if ($item)
                    $query->where($index, $item);
            }
            return $query;
        });
        if ($model) $find->where('model', $model);
        if ($id) $find->where('model_id', $id);
        if ($key) $find->where('key', $key);
        return $find->first();
    }

    public static function findByPhone($model, $id, $key, $country, $prefix, $number, $type = 'tel')
    {
        return static::where('model', $model)->where('model_id', $id)->where('key', $key)->where('country', $country)->where('prefix', $prefix)->where('number', $number)->where('type', $type)->first();
    }

    public static function getByModel($model, $id, $key = null, $type = null)
    {
        $get  = static::where('model', $model)->where('model_id', $id)->where('key', $key);
        if ($key) $get->where('key', $key);
        if ($type) $get->where('type', $type);
        return $get->get();
    }

    public function getTextAttribute()
    {
        return $this->country.$this->prefix.$this->number;
    }

    public function item() {
        if ($this->model){
            $model = imodal($this->model);
            return $model::find($this->model_id);
        }
        return null;
    }
}
