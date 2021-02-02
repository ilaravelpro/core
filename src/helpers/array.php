<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
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
            if (_get_value($requestArray, $bfield)){
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
