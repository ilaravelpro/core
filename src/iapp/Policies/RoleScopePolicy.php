<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class RoleScopePolicy extends iRolePolicy
{
    public $prefix = 'roles';
    public $parent = 'Role';
    public $model = 'RoleScope';
}
