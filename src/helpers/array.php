<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/1/21, 3:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

function insert_into_array( $array, $search_key, $insert_key, $insert_value, $insert_after_founded_key = true, $append_if_not_found = false ) {

    $new_array = array();

    foreach( $array as $key => $value ){

        // INSERT BEFORE THE CURRENT KEY?
        // ONLY IF CURRENT KEY IS THE KEY WE ARE SEARCHING FOR, AND WE WANT TO INSERT BEFORE THAT FOUNDED KEY
        if( $key === $search_key && ! $insert_after_founded_key )
            $new_array[ $insert_key ] = $insert_value;

        // COPY THE CURRENT KEY/VALUE FROM OLD ARRAY TO A NEW ARRAY
        $new_array[ $key ] = $value;

        // INSERT AFTER THE CURRENT KEY?
        // ONLY IF CURRENT KEY IS THE KEY WE ARE SEARCHING FOR, AND WE WANT TO INSERT AFTER THAT FOUNDED KEY
        if( $key === $search_key && $insert_after_founded_key )
            $new_array[ $insert_key ] = $insert_value;

    }

    // APPEND IF KEY ISNT FOUNDED
    if( $append_if_not_found && count( $array ) == count( $new_array ) )
        $new_array[ $insert_key ] = $insert_value;

    return $new_array;

}

function is_json($string) {
    if (is_array($string))
        return false;
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function remove_empty($array, $max = null) {
    if (is_object($array)) $array = (array) $array;
    return array_filter($array, function ($item, $key) use ($max) {
        return is_array($item) || is_object($item) ? count((array) $item) : strlen($item) && ($max ? $key <= $max: true);
    }, ARRAY_FILTER_USE_BOTH );
}

function handel_fields($except, $fields, $requestArray) {
    foreach ($except as $key => $value) {
        $fields = array_filter($fields, function ($field) use ($value) {
            return !(substr($field, 0, strlen($value)) == $value);
        });
    }
    $child = [];
    foreach ($fields as $index => $field) {
        if (strpos($field, '*') !== false){
            unset($fields[$index]);
            $other[] = $field;
            $efield = array_filter(explode('.*', $field),function ($fi) { return $fi;});
            $bfield = $efield[0];
            unset($efield[0]);
            if (_get_value($requestArray, $bfield) && is_array(_get_value($requestArray, $bfield))){
                $fieldChild = [];
                foreach (_get_value($requestArray, $bfield) as $i => $item) {
                    $fieldChild[$i] = "$bfield.$i";
                    if (count($efield))
                        $fieldChild[$i] .= implode('.*', $efield);
                }
                if (count($fieldChild)){
                    $fieldChild = handel_fields([], $fieldChild, $requestArray);
                }
                $child = array_merge($child, $fieldChild);
            }
        }
    }
    return array_merge(array_values($fields), array_values($child));
}

// str_slice(string $str, int $start [, int $end])
function str_slice($string, ...$args) {
    $startStr = $args[count($args) - 1] == 's';
    if (in_array($args[count($args) - 1], ['s', 'e']))
        unset($args[count($args) - 1]);
    if (!isset($args[1]))
        $args[1] = strlen($string);
    elseif (isset($args[1]) && !is_numeric($args[1]))
        $args[2] = strlen($string);
    switch (count($args)) {
        case 1:
            return $startStr ? ($string.$args[0]) : ($args[0].$string);
        case 2:
            $str        = $args[0];
            $str_length = strlen($str);
            $start      = $args[1];
            if ($start < 0) {
                if ($start >= - $str_length) {
                    $start = $str_length - abs($start);
                } else {
                    $start = 0;
                }
            }
            else if ($start >= $str_length) {
                $start = $str_length;
            }
            $length = $str_length - $start;
            return $startStr ? ($string.substr($str, $start, $length)) : (substr($str, $start, $length).$string);
        case 3:
            $str        = $args[0];
            $str_length = strlen($str);
            $start      = $args[1];
            $end        = $args[2];
            if ($start >= $str_length) {
                return "";
            }
            if ($start < 0) {
                if ($start < - $str_length) {
                    $start = 0;
                } else {
                    $start = $str_length - abs($start);
                }
            }
            if ($end <= $start) {
                return "";
            }
            if ($end > $str_length) {
                $end = $str_length;
            }
            $length = $end - $start;
            return $startStr ? ($string.substr($str, $start, $length)) : (substr($str, $start, $length).$string);
    }
    return null;
}

function _set_values_text($text, $values)
{
    $replace_values = array_map(function($replace_value) {return ":{$replace_value}";}, array_keys($values));
    $text = str_replace($replace_values, array_values($values), $text);
    return $text;
}
