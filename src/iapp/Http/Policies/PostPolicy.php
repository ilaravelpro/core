<?php

namespace iLaravel\Core\IApp\Http\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class PostPolicy extends iRolePolicy
{
    public $prefix = 'posts';
    public $model = 'Post';
}
