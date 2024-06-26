<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/24/20, 11:20 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor\iRole;


use App\User;

class iRoleCheck
{
    protected $user = null;
    public $role = null;
    public $scopes = [];

    public function __construct($user)
    {
        $this->user = $user;
        $this->role = imodal('Role');
        $this->role = $this->role::findByName($this->user->role);
        $this->scopes = $this->user->scopeAll();
    }

    public function scopes($keys = false)
    {
        return $keys ? array_keys($this->scopes) : $this->scopes;
    }

    public function get($key)
    {
        return $this->has($key) ? (isset($this->scopes[$key]) ? $this->scopes[$key] : null) : false;
    }

    public function has($access, ...$args)
    {
        $admins = ipreference('admins', ['admin']);
        ///if (in_array($this->user->role, $admins)) return true;
        $access = !is_array($access) ? [$access] : $access;
        $scopes = $this->scopes();
        foreach ($access as $key => $value) {
            $value = str_replace(" ", "", $value);
            if (strpos($value, '|')) {
                $OrValue = explode('|', $value);
                $check = false;
                foreach ($OrValue as $okey => $ovalue) {
                    if (substr($ovalue, 0, 1) == '@') {
                        if ($this->inGroup(substr($ovalue, 1))) {
                            $check = true;
                            break;
                        }
                    } else {
                        if (isset($scopes[$ovalue]) && $scopes[$ovalue]) {
                            $check = true;
                            break;
                        }
                    }
                }
                if (!$check) {
                    return false;
                }
                continue;
            } elseif (substr($value, 0, 1) == '@') {
                if (!$this->inGroup(substr($value, 1))) {
                    return false;
                }
            } elseif (isset($scopes[$value]) && !$scopes[$value]) {
                return false;
            }
        }
        return true;
    }

    public function type($search = null)
    {
        if (!auth()->check()) {
            return false;
        }
        if ($search) {
            return $this->user->type == $search;
        }
        return $this->user->type;
    }

    public function groups($search = null)
    {
        if (!auth()->check()) {
            return false;
        }
        $groups = $this->user->groups ? explode('|', $this->user->groups) : [];
        if (!in_array($this->user->type, $groups)) {
            array_push($groups, $this->user->type);
        }
        return $groups;
    }

    public function inGroup($search)
    {
        if (!auth()->check()) {
            return false;
        }
        if (in_array($search, $this->groups())) {
            return true;
        }
        return false;
    }

    public static function getRules()
    {
        return auth()->check() ? auth()->user()->scopeAllUnique() : User::guest()->scopeAll();
    }

    public static function getRulesUnique()
    {
        return auth()->check() ? auth()->user()->scopeAllUnique() : User::guest()->scopeAllUnique();
    }
}
