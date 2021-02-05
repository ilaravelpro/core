<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/25/20, 7:35 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor\iRole;

use Illuminate\Auth\Access\HandlesAuthorization;

class iRole
{
    use HandlesAuthorization;
    static protected $users = [];
    public $role = null;

    public function __construct()
    {
        $this->role = imodal('Role');
    }

    public static function user($user)
    {
        if (!isset(static::$users[$user->id])) {
            static::$users[$user->id] = new iRoleCheck($user);
        }
        return static::$users[$user->id];
    }

    public static function has($access, ...$args)
    {
        if (!auth()->check()) return false;
        return static::user(auth()->user())->has($access, ...$args);
    }

    public static function scopes($scopes = null , $model = null, $canDef = 0)
    {
        $configScopes = iconfig('scopes', []);
        if ($model) {
            unset($configScopes['global']);
            $id = -1;
            foreach ($configScopes as $rkey => $role) {
                foreach ($role['items'] as $index => $sec) {
                    if (is_array($sec)) {
                        foreach ($sec as $keyd => $valued) {
                            if (!$scopes->where('scope', "$rkey.$index.$valued")->first()) {
                                $item = new $model;
                                $item->id = $id;
                                $item->scope = "$rkey.$index.$valued";
                                $item->can = $canDef;
                                $scopes->add($item);
                                $id--;
                            }
                        }
                    } elseif (!$scopes->where('scope', "$rkey.$sec")->first()) {
                        $item = new $model;
                        $item->id = $id;
                        $item->scope = "$rkey.$sec";
                        $item->can = $canDef;
                        $scopes->add($item);
                        $id--;
                    }
                }

            }
        }
        return $scopes;
    }

    public static function permissions($key = false)
    {
        return static::user(auth()->user())->permissions($key);
    }

    public static function get($key)
    {
        return static::user(auth()->user())->get($key);
    }

    public static function users()
    {
        return static::$users;
    }

    protected function denyAccess($message = 'This action is unauthorized.')
    {
        abort(403, $message);
    }
}
