<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

class _PostMeta extends \iLaravel\Core\iApp\Methods\MetaData
{
    use Modal;
    public static $s_prefix = 'IPM';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $table = 'post_meta';

    protected $guarded = [];
}
