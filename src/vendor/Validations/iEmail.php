<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/13/20, 7:13 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor\Validations;

class iEmail {
    public static function parse($value, $parameters = null)
    {
        if (is_array($value) || is_object($value)){
            $value = (array) $value;
            $email = null;
            foreach (['name', 'email'] as $item)
                if (isset($value[$item]) && $value[$item])
                    $email .= $value[$item];
            if (!$email) return false;
            $value = $email;
        }
        $re = '/(^[a-zA-Z0-9._-]*)+@+([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,})$/';
        preg_match($re, $value, $matches);
        $email = [];
        list($email['full'], $email['name'], $email['domain']) = $matches;
        return $email;
    }
}
