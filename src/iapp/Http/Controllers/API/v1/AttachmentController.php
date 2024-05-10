<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/21/21, 12:38 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Http\Controllers\API\Controller;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Index;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Show;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Update;
use iLaravel\Core\iApp\Http\Controllers\API\Methods\Controller\Destroy;

class AttachmentController extends Controller
{
    public function filters($request, $model, $parent = null, $operators = [])
    {
        return [[],[],[]];
    }

    use
        Index,
        Show,
        Attachment\Editors,
        Attachment\Store,
        Update,
        Destroy,
        Attachment\Filters;
}
