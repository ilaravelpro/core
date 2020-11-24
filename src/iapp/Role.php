<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Role extends Eloquent
{
    use Modals\Modal;
    public static $s_prefix = 'IR';
    public static $s_start = 30;
    public static $s_end = 899;

    protected $guarded = [];

    protected $hidden = ['scopes'];

    public function scopes()
    {
        return $this->hasMany(imodal('RoleScope'));
    }

    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }

    public static function admin($name = 'admin') {
        return new static([
            'id' => 0,
            'name' => $name,
            'title' => ucfirst($name),
        ]);
    }
}
