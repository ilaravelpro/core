<?php

function i_path($path = null)
{
    $path = trim($path, '/');
    return dirname(__DIR__, 1) . ($path ? "/$path" : '');
}

function iconfig($key = null, $default = null)
{
    $cilaravel =  config('ilaravel.main'.($key ? ".$key" : ''));
    $iilaravel =  config('ilaravel'.($key ? ".$key" : ''));
    $ilaravel = $iilaravel === null ? $cilaravel : $iilaravel;
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

function ipreference($key = null, $default = null, $type = 'auto')
{
    if ($key && in_array($type, ['auto', 'db'])){
        $model = imodal('Preference');
        $path = explode('.', $key);
        $value = $model::findBySectionName($path[0], isset($path[1]) ? $path[1] : null);
        if($value) {
            if (count($path) > 2){
                unset($path[0]);
                unset($path[1]);
                $value = _get_value($value->value, implode('.', $path));
            }else
                $value = $value->value;
            $type = 'db';
        }
    }
    if (in_array($type, ['auto', 'config']))
        $value = $key ? iconfig('preferences.'.$key, $default) : iconfig('preferences', $default);
    return $value;
}

