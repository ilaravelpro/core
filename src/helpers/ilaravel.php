<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/24/20, 4:22 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

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
        $section = $path[0];
        array_shift($path);
        $rpath = implode('.', $path);
        if ($value = $model::findBySectionName($section, $rpath)) {
            return $value->value;
        }
        if (count($path))$value = $model::findBySectionName($section, $path[0]);
        if($value) {
            if (count($path) > 1){
                unset($path[0]);
                $value = _get_value($value->value, implode('.', $path));
            }else
                $value = $value->value;
            $type = 'db';
        }
    }
    if (in_array($type, ['auto', 'config']))
        $value = $key ? iconfig('preferences.'.$key, iconfig($key, $default) ) : iconfig('preferences', $default);
    return $value;
}
function upreference($key = null, $value = null, $type = 'merge')
{
    $model = imodal('Preference');
    $path = explode('.', $key);
    $section = $path[0];
    $name = $path[1];
    unset($path[0],$path[1]);
    $rpath = implode('.', $path);
    if ($item = $model::findBySectionName($section, $name)) {
        $item_value = $item->value;
        $item->value = _set_value($item_value, $rpath, $value, false);
        $item->save();
        return $item;
    }
    return $model::updateOrCreate(['section' => $section, 'name' => $name . "." . $rpath], ['value' => $value]);
}

function _has_token(){
    return app('request')->header('authorization');
}
