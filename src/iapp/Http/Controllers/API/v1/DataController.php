<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;

class DataController extends Controller
{
    use Data\Roles,
        Data\Users,
        Data\Status,
        Data\Scopes;
}
