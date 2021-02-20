<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/8/21, 7:42 AM
 * Copyright (c) 2021. Powered by iamir.net
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

function _add_get_method($url, $parameters) {
    $url_parts = parse_url($url);
    // If URL doesn't have a query string.
    if (isset($url_parts['query'])) { // Avoid 'Undefined index: query'
        parse_str($url_parts['query'], $params);
    } else {
        $params = array();
    }
    $params = array_merge($params, $parameters);
    $url_parts['query'] = http_build_query($params);
    return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
}
