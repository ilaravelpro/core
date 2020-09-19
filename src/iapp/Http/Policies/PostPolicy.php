<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class PostPolicy extends iRolePolicy
{
    public $prefix = 'posts';
    public $model = 'Post';
}
