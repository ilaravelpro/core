<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/12/20, 8:55 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;


class _Preference extends \iLaravel\Core\iApp\Methods\MetaData
{
    use Modal;
    public static $s_prefix = 'IP';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $guarded = [];

    public static function findBySectionName($section, $name)
    {
        return static::where('section', $section)
            ->where('name', $name)
            ->first();
    }

    public function setValueAttribute($value)
    {
        $this->renderSetValue($value);
    }

    public function getValueAttribute($value)
    {
        return $this->renderGetValue($value);
    }
}
