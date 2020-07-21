<?php

namespace iLaravel\Core\iApp\Http\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class UserScopePolicy extends iRolePolicy
{
    public $prefix = 'users';
    public $parent = 'User';
    public $model = 'UserScope';
}
