<?php

namespace iLaravel\Core\IApp;

use Illuminate\Database\Eloquent\Model as Eloquent;

class RoleScope extends Eloquent
{
    use Modals\Modal;
    public static $s_prefix = 'IRS';
    public static $s_start = 900;
    public static $s_end = 26999;

    protected $guarded = [];
}
