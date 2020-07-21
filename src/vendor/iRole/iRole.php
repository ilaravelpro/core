<?php

namespace iLaravel\Core\Vendor\iRole;

use Illuminate\Support\Facades\Gate;
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

    public static function scopes($scopes = null , $model = null)
    {
        $configScopes = iconfig('scopes', []);
        if ($model) {
            unset($configScopes['global']);
            $id = -1;
            foreach ($configScopes as $rkey => $role) {
                foreach ($role as $index => $sec) {
                    if (is_array($sec)) {
                        foreach ($sec as $keyd => $valued) {
                            if (!$scopes->where('scope', $valued)->first()) {
                                $item = new $model;
                                $item->id = $id;
                                $item->scope = "$rkey.$index.$valued";
                                $item->can = 0;
                                $scopes->add($item);
                                $id--;
                            }
                        }
                    } else if (!$scopes->where('scope', $sec)->first()) {
                        $item = new $model;
                        $item->id = null;
                        $item->scope = "$rkey.$sec";
                        $item->can = 0;
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
