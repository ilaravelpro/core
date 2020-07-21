<?php

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
