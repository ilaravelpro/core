<?php

namespace iLaravel\Core\iApp;


class Performance extends Modals\MetaData
{
    use Modals\Modal;
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
}
