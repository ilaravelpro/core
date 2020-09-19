<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Policies;

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
