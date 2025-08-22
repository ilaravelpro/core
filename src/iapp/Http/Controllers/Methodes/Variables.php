<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;


trait Variables
{
    public $index_mts_cached = 5;
    protected $fillable = [];
    public static $result;
    public $statusMessage = 'Your request has been successfully completed.';
}
