<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 5:27 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor\Validations;

class iEmail {

    public static function parse($value, $parameters = null)
    {
        $model = imodal('Email');
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
        if ($parameters && is_array($parameters) && in_array('unique', $parameters))
            return $model::where('model', _get_value($parameters, '1', 'User'))
                ->where('key', _get_value($parameters, '2', 'email'))
                ->where('name', $email['name'])
                ->where('domain', $email['domain'])
                ->first() ? false : $email;
        return $email;
    }
}
