<?php

namespace iLaravel\Core\IApp;

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
        return $this->hasMany(RoleScope::class);
    }

    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }
}
