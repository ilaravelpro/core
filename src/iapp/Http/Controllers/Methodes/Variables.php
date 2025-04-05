<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;


trait Variables
{
    public $index_time_cached = 60;
    protected $fillable = [];
    public static $result;
    public $statusMessage = 'Your request has been successfully completed.';
}
