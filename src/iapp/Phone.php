<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;

use iLaravel\Core\Vendor\iMobile;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Phone extends Eloquent
{
    use Modals\Modal;
    public static $s_prefix = 'ILCP';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $guarded = [];

    public static function findByMobile($model, $id, $key, $mobile = null)
    {
        $mobile = iMobile::parse($mobile);
        return static::where('model', $model)->where('model_id', $id)->where('key', $key)->where('country', $mobile['code'])->where('number', $mobile['number'])->where('type', 'mobile')->first();
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
}
