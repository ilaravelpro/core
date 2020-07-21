<?php

function i_path($path = null)
{
    $path = trim($path, '/');
    return dirname(__DIR__, 1) . ($path ? "/$path" : '');
}

function iconfig($key = null, $default = null)
{
    $cilaravel =  config('iamir.ilaravel'.($key ? ".$key" : ''));
    $iilaravel =  config('ilaravel'.($key ? ".$key" : ''));
    $ilaravel = $cilaravel === null ? $iilaravel : $cilaravel;
    if (is_array($cilaravel) || is_array($iilaravel)){
        $ilaravel = array_merge((array) $iilaravel, (array)$cilaravel);
        $ilaravel =  array_replace_recursive($ilaravel,
            array_intersect_key(
                (array)$iilaravel, $ilaravel
            )
        );
    }
    return $ilaravel !== null ? $ilaravel : $default;
}

function ipreference($key = null, $default = null)
{
    return $key ? iconfig('preferences.'.$key, $default) : iconfig('preferences', $default);
}

