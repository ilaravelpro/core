<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/28/21, 1:30 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API;
class ApiController extends Controller
{
    use Methods\Controller\Index,
        Methods\Controller\Data,
        Methods\Controller\Show,
        Methods\Controller\Store,
        Methods\Controller\Update,
        Methods\Controller\Destroy;
}
