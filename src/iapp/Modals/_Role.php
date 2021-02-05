<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 11:25 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Illuminate\Database\Eloquent\Model as Eloquent;

class _Role extends Eloquent
{
    use Modal;
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
