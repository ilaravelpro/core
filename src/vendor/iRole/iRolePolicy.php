<?php

namespace iLaravel\Core\Vendor\iRole;

class iRolePolicy extends iRole
{
    public $prefix = null;
    public $model = null;
    public $scopes = [];

    public function __construct()
    {
        parent::__construct();
        if (!$this->prefix){
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
        if (isset($this->parent)){
            return $this->view($user,null, $this->parentModel::findBySerial($parent));
        }else
            foreach (iconfig('scopes.'.$this->prefix.'.view') as $view)
                return static::has($view);
        return false;
    }

    public function view($user, $item)
    {
        return $this->single(...array_merge(func_get_args(), ['view']));
    }

    public function create($user, $item = null)
    {
        return static::has($this->prefix.'.create');
    }

    public function update($user, $item)
    {
        return $this->single(...array_merge(func_get_args(), ['edit']));
    }

    public function delete($user, $item)
    {
        return $this->single(...array_merge(func_get_args(), ['destroy']));
    }

    public function single($user, $item,  $child = null, $action = null, ...$args)
    {
        if (isset($this->parent) && is_string($item))
            $item = $this->parentModel::findBySerial($item);
        if (!$action) $action = $child;
        foreach (iconfig("scopes.$this->prefix.$action") as $any) {
            switch ($any) {
                case 'any':
                    return static::has("$this->prefix.$action.$any");
                case 'anyByUser':
                default:
                    return static::has("$this->prefix.$action.$any") && isset($item->creator_id) && $item->creator_id == $user->id;
            }
        }
        return false;
    }
}
