<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1;

use iLaravel\Core\IApp\Http\Controllers\API\Controller;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Index;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Show;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Store;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Update;
use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Destroy;

class PostController extends Controller
{
    public $order_list = ['id', 'title', 'content', 'summary', 'url', 'slug', 'meta', 'order', 'type', 'parent'];

    use Index,
        Show,
        Store,
        Update,
        Destroy,
        Post\Filters,
        Post\Rules;
}
