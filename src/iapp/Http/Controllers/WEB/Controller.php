<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB;

use iLaravel\Core\IApp\Http\Controllers\iController;

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
