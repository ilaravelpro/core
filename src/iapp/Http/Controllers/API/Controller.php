<?php

namespace iLaravel\Core\iApp\Http\Controllers\API;

use iLaravel\Core\iApp\Http\Controllers\iController;

class Controller extends iController
{
    public $clientController = null;

    use Methods\Construct,
        Methods\Index,
        Methods\Show,
        Methods\Store,
        Methods\Update,
        Methods\Destroy,
        Methods\RequestFilter;
}
