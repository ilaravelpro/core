<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;

use iLaravel\Core\iApp\Modals\MetaData;

class UserMeta extends MetaData
{
    use Modals\Modal;
    public static $s_prefix = 'IUM';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $table = 'user_meta';

    protected $guarded = [];
}
