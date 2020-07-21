<?php

namespace iLaravel\Core\IApp\Http\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class UserScopePolicy extends iRolePolicy
{
    public $prefix = 'users';
    public $parent = 'User';
    public $model = 'UserScope';
}
