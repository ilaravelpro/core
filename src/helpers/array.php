<?php

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
