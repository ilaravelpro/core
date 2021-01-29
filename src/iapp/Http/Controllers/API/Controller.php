<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API;

use iLaravel\Core\iApp\Http\Controllers\iController;

class Controller extends iController
{
    public $clientController = null;

    use Methods\Construct,
        Methods\Index,
        Methods\Data,
        Methods\Show,
        Methods\Store,
        Methods\Update,
        Methods\Destroy,
        Methods\RequestFilter,
        Methods\HandelFields;
}
