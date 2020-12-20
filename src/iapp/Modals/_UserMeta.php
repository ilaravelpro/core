<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/16/20, 8:23 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

class _UserMeta extends \iLaravel\Core\iApp\Methods\MetaData
{
    use Modal;
    public static $s_prefix = 'IUM';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $table = 'user_meta';

    protected $guarded = [];
}
