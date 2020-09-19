<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

function format_datetime($datetime, $format, $attr) {
    if (request('format'))
        if (is_array(request('format')) || is_json(request('format'))){
            $format = is_json(request('format')) ? array_merge(json_decode(request('format')), $format) : array_merge(request('format'), $format);
        }else
            $format = request('format');
    if (is_array($format))
        $format = isset($format[$attr]) ? $format[$attr] : $format['global'];
    if (ipreference('lang') == 'fa')
        $datetime =  jdate($datetime)->format($format);
    else
        $datetime =  \Carbon\Carbon::parse($datetime)->format($format);
    return $datetime;
}
