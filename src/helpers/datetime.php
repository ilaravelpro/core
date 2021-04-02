<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

function format_datetime($datetime, $format, $attr) {
    if (!$datetime) return $datetime;
    if (request('format'))
        if (is_array(request('format')) || is_json(request('format'))){
            $format = is_json(request('format')) ? array_merge(json_decode(request('format')), $format) : array_merge(request('format'), $format);
        }else
            $format = request('format');
    $format = is_array($format) && isset($format[$attr]) ? $format[$attr] : 'Y-m-d H:i:s';
    if (ipreference('lang') == 'fa')
        $datetime =  jdate($datetime)->format($format);
    else
        $datetime =  \Carbon\Carbon::parse($datetime)->format($format);
    return $datetime;
}
