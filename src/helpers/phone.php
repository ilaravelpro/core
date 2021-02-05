<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

function convMobile($mobile) {
    if (strpos($mobile, '-') !== false) {
        $mobile = explode('-', $mobile);
        return [
            'country' => $mobile[0],
            'number' => (int) str_replace('98', '', (string) $mobile[1])
        ];
    }else{
        return [
            'country' => 98,
            'number' => (int) str_replace('98', '', (string) $mobile)
        ];
    }
}
