<?php

namespace iLaravel\Core\iApp;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserScope extends Eloquent
{
    use Modals\Modal;
    public static $s_prefix = 'IUS';
    public static $s_start = 900;
    public static $s_end = 26999;


    protected $guarded = [];
}
