<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class UserScopePolicy extends iRolePolicy
{
    public $prefix = 'users';
    public $parent = 'User';
    public $model = 'UserScope';
}
