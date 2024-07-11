<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/28/21, 1:30 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API;
class ApiChildController extends Controller
{
    use Methods\Controller\Child\Index,
        Methods\Controller\Child\Data,
        Methods\Controller\Child\Show,
        Methods\Controller\Child\Store,
        Methods\Controller\Child\Update,
        Methods\Controller\Child\Destroy;
}
