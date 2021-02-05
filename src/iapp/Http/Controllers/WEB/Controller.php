<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB;

use iLaravel\Core\iApp\Http\Controllers\iController;

class Controller extends iController
{
    use Methods\Construct,
        Methods\DesignConstruct,
        Methods\View,
        Methods\Rules,
        Methods\Authorizations,
        Methods\Endpoint,
        Methods\RtoA,
        Methods\Index,
        Methods\Show,
        Methods\Create,
        Methods\Edit,
        Methods\Destroy,
        Methods\webUpdate,
        Methods\webStore,
        Methods\webDestroy;
}
