<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 5:21 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor\Validations;

class iMobile {
    public static function parse($value, $parameters = null)
    {
        $value = array_filter($value, 'strlen');
        if(!preg_match("#^\+?\d+#", $value))
        {
            return false;
        }
        $country_codes = array_column(iconfig('countries'), 'code', 'iso');
        $country_iso = array_column(iconfig('countries'), 'iso', 'iso');
        $mobile_codes = array_column(iconfig('countries'), 'prefix', 'iso');
        $mobile_country = null;
        $country = isset($parameters[0]) ? $parameters[0] : '*';
        if (!$country == '*' && !in_array($country, $country_iso)) {
            return false;
        }
        if (substr($value, 0, 2) === '00' || substr($value, 0, 1) === '+') {
            if (substr($value, 0, 2) === '00') {
                $value = substr($value, 2);
            } else {
                $value = substr($value, 1);
            }
            foreach ($country_codes as $key => $code) {
                if (substr($value, 0, strlen($code)) == $code) {
                    $value = substr($value, strlen($code));
                    $mobile_country = $key;
                    break;
                }
            }
            if (!$mobile_country) {
                return false;
            }
        } elseif (substr($value, 0, 1) === '0') {
            $value = substr($value, 1);
        } elseif (strlen($value) > 10) {
            foreach ($country_codes as $key => $code) {
                if (substr($value, 0, strlen($code)) == $code) {
                    $value = substr($value, strlen($code));
                    $mobile_country = $key;
                    break;
                }
            }
            if (!$mobile_country) {
                return false;
            }
        }

        if (strlen($value) != 10) {
            return false;
        }
        foreach ($mobile_codes as $key => $codes) {
            $code = substr($value, 0, 2);
            if (in_array($code, $codes)) {
                if ($mobile_country == $key || !$mobile_country) {
                    $mobile_country = $key;
                    break;
                } else {
                    return false;
                }
            }
        }
        return ['number' => $value, 'code' => $country_codes[$mobile_country], 'iso' => $mobile_country];
    }
}
