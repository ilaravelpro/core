<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;

use Illuminate\Database\Eloquent\Model as Eloquent;

class RoleScope extends Eloquent
{
    use Modals\Modal;
    public static $s_prefix = 'IRS';
    public static $s_start = 900;
    public static $s_end = 26999;

    protected $guarded = [];
}
