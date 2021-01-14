<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

include 'ilaravel.php';
include 'datetime.php';
include 'array.php';
include 'debug.php';
include 'phone.php';
include 'finder.php';
include 'measuring.php';
include 'math.php';

function _t($trans)
{
    return $trans;
}

function random_filename($length, $directory = '', $extension = '')
{
    // default to this files directory if empty...
    $dir = !empty($directory) && is_dir($directory) ? $directory : dirname(__FILE__);

    do {
        $key = \Illuminate\Support\Str::random($length);
    } while (file_exists($dir . '/' . $key . (!empty($extension) ? '.' . $extension : '')));

    return $key . (!empty($extension) ? '.' . $extension : '');
}

// Checks in the directory of where this file is located.
//echo random_filename(50);

// Checks in a user-supplied directory...
//echo random_filename(50, '/ServerRoot/mysite/myfiles');

// Checks in current directory of php file, with zip extension...
//echo random_filename(50, '', 'zip');
