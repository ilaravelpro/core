<?php

namespace iLaravel\Core\IApp\Http\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class UserPolicy extends iRolePolicy
{
    public $prefix = 'users';
    public $model = 'User';

    public function isAdmin($user, $item, $action, ...$args)
    {
        return $user->isAdmin();
    }
}
