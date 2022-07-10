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

/*if (!function_exists('_t')){
    function _t($trans)
    {
        return $trans;
    }
}*/

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

function to_slug($string, $separator = '-')
{
    $string = trim($string);
    $string = mb_strtolower($string, 'UTF-8');

    $string = preg_replace("/[^a-z0-9_\s\-ءاآؤئبپتثجچحخدذرزژسشصضطظعغفقكکگلمنوهی]/u", '', $string);

    $string = preg_replace("/[\s\-_]+/", ' ', $string);

    $string = preg_replace("/[\s_]/", $separator, $string);
    return $string;
}

function redirect_post($url, array $data) {
    $hiddenFields = '';
    foreach ($data as $key => $value) {
        $hiddenFields .= sprintf(
                '<input type="hidden" name="%1$s" value="%2$s" />',
                htmlentities($key, ENT_QUOTES, 'UTF-8', false),
                htmlentities($value, ENT_QUOTES, 'UTF-8', false)
            )."\n";
    }

    $output = '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>'._t("Redirecting...").'</title>
</head>
<body onload="document.forms[0].submit();">
    <form action="%1$s" method="post">
        <p>'._t("Redirecting to page...").'</p>
        <p>
            %2$s
            <input type="submit" value="'._t("Continue").'" />
        </p>
    </form>
</body>
</html>';
    $output = sprintf(
        $output,
        htmlentities($url, ENT_QUOTES, 'UTF-8', false),
        $hiddenFields
    );

    return $output;
}

function _is_local_host($whitelist = ['127.0.0.1', '::1'])
{
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

function _is_windows()
{
    return strtolower(PHP_OS_FAMILY) == 'windows';
}

function _reset_path($path)
{
    return _is_windows() == 'windows' ? str_replace('/', '\\', $path) : str_replace('\\', '/', $path);
}

function _level_password($pwd) {
    $level = 0;
    if (strlen($pwd) > 8) $level++;
    if (preg_match("#[0-9]+#", $pwd)) $level++;
    if (preg_match("#[a-zA-Z]+#", $pwd)) $level++;
    return $level;
}