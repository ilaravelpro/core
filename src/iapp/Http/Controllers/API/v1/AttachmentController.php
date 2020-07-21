<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1;

use iLaravel\Core\IApp\Http\Controllers\API\Controller;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Index;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Show;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Update;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Destroy;

class AttachmentController extends Controller
{
    use
        Attachment\Construct,
        Index,
        Show,
        Attachment\Store,
        Update,
        Destroy,
        Attachment\Fields,
        Attachment\Filters,
        Attachment\Rules;
}
