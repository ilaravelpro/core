<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Illuminate\Database\Eloquent\Model as Eloquent;

class _RoleScope extends Eloquent
{
    use Modal;
    public static $s_prefix = 'IRS';
    public static $s_start = 900;
    public static $s_end = 26999;

    protected $guarded = [];
}
