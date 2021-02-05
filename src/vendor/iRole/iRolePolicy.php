<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 11:16 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor\iRole;

class iRolePolicy extends iRole
{
    public $prefix = null;
    public $model = null;
    public $scopes = [];

    public function __construct()
    {
        parent::__construct();
        if (!$this->prefix) {
            $as = explode('.', str_replace('api.', '', request()->route()->getAction('as')));
            $this->prefix = $as[0];
        }
        if ($this->model)
            $this->model = imodal($this->model);
        else
            $this->model = request()->route()->getController()->model;
        if (isset($this->parent)) $this->parentModel = imodal($this->parent);
        //else $this->parentModel = request()->route()->getController()->parentModel;

        $this->scopes = ipreference('scopeSubs');
    }

    public function viewAny($user, $parent = null)
    {
        if (isset($this->parent)) {
            return $this->view($user, null, $this->parentModel::findBySerial($parent));
        } else
            foreach (iconfig('scopes.' . $this->prefix . '.items.view') as $view)
                if ($can = static::has($this->prefix . '.view.' . $view)) return $can;
        return false;
    }

    public function data($user, $parent = null)
    {
        if (isset($this->parent)) {
            return $this->view($user, null, $this->parentModel::findBySerial($parent));
        } else
            foreach (iconfig('scopes.' . $this->prefix . '.items.data') as $view)
                if ($can = static::has($this->prefix . '.data.' . $view)) return $can;
        return false;
    }

    public function view($user, $item, ...$args)
    {
        return $this->single(...array_merge(func_get_args(), ['view']));
    }

    public function create($user, $item = null, ...$args)
    {
        return static::has($this->prefix . '.create');
    }

    public function update($user, $item, ...$args)
    {
        return $this->single(...array_merge(func_get_args(), ['edit']));
    }

    public function delete($user, $item, ...$args)
    {
        return $this->single(...array_merge(func_get_args(), ['destroy']));
    }

    public function single($user, $item, $child = null, $action = null, ...$args)
    {
        if (method_exists($this, 'handelModal')){
            list($item, $child) = $this->handelModal($item, $child);
        }else{
            if (isset($this->parent) && is_string($item))
                $item = $this->parentModel::findBySerial($item);
            if(is_string($item)) {
                $item = $this->model::findBySerial($item);
            }
            if(is_string($child) && $this->model::id($child)) {
                $child = $this->model::findBySerial($child);
            }
        }
        if (!$action) $action = $child;
        $anyByUser = function ($context, $sub, $user, $item, $child = null, $action = null, $args = null) {
            if ($can = static::has("$context->prefix.$action.$sub") && (
                    (isset($item->creator_id) && $item->creator_id == $user->id) ||
                    (isset($item->user_id) && $item->user_id == $user->id) ||
                    (isset($item->{auth()->user()->role . "_id"}) && $item->{auth()->user()->role . "_id"} == $user->id)
                )) return $can;
            return false;
        };
        foreach (iconfig("scopes.$this->prefix.items.$action", []) as $any) {
            if (static::has("$this->prefix.$action.$any")){
                if (function_exists('i_role_policy_single_switch'))
                    return i_role_policy_single_switch($this, $anyByUser, $any, $user, $item, $child, $action, $args);
                else
                    switch ($any) {
                        case 'any':
                            if ($can = static::has("$this->prefix.$action.$any")) return $can;
                            break;
                        case 'anyByUser':
                        default:
                            if (function_exists('i_role_policy_single_switch_default'))
                                return i_role_policy_single_switch_default($this, $anyByUser, $any, $user, $item, $child, $action, $args);
                            else return $anyByUser($this, $any, $user, $item, $child, $action, $args);
                            break;
                    }
            }
        }
        return false;
    }
}
