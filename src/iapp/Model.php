<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;


class Model extends \Illuminate\Database\Eloquent\Model
{
    use \iLaravel\Core\iApp\Modals\Modal;

    protected $guarded = ['id',];
}
