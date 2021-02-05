<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 11:25 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Illuminate\Database\Eloquent\Model as Eloquent;

class _UserScope extends Eloquent
{
    use Modal;
    public static $s_prefix = 'IUS';
    public static $s_start = 900;
    public static $s_end = 26999;


    protected $guarded = [];
}

