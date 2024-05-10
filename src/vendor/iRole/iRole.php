<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/25/20, 7:35 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor\iRole;

use iLaravel\Core\iApp\Role;
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
        $scopes = collect($scopes->map(function ($scope) {
            return [
                'id' => $scope->id,
                'scope' => $scope->scope,
                'can' => $scope->can,
            ];
        })->toArray());
        if (isset($configScopes['users']['items']) && $configScopes['users']['items']) {
            $configScopes['users']['items']['fields']['role'] = Role::all()->pluck('name')->toArray();
        }
        if ($model) {
            unset($configScopes['global']);
            $id = -1;
            foreach ($configScopes as $rkey => $role) {
                foreach ($role['items'] as $index => $sec) {
                    list($scopes, $id) = static::renderScopes($scopes, $model, $id, $sec, is_array($sec) ? "$rkey.$index" : "$rkey.$sec", $canDef);
                }
            }
        }
        return $scopes;
    }

    public static function renderScopes($scopes, $model, $id, $sec, $key, $canDef = 0)
    {
        if (is_array($sec)) {
            foreach ($sec as $i => $valued)
                list($scopes, $id) = static::renderScopes($scopes, $model, $id, $valued, is_array($valued) ? "$key.$i" : "$key.$valued", $canDef);
        } elseif (!$scopes->where('scope', $key)->first()) {
            $scopes->add([
                'id' => $id,
                'scope' => $key,
                'can' => $canDef,
            ]);
            $id--;
        }
        return [$scopes, $id];
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
