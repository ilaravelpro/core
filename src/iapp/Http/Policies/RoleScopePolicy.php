<?php

namespace iLaravel\Core\iApp\Http\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class RoleScopePolicy extends iRolePolicy
{
    public $prefix = 'roles';
    public $parent = 'Role';
    public $model = 'RoleScope';
}
