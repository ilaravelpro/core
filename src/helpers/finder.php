<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 8:13 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

use Illuminate\Support\Str;

function i_class_exists($patch, $class)
{
    foreach (iconfig('plugins') as $plugin) {
        if (class_exists("\\iLaravel\\$plugin\\$patch\\$class"))
            return "\\iLaravel\\$plugin\\$patch\\$class";
    }
    return false;
}

function imodal($modal)
{
    return class_exists("\\App\\$modal") ? "\\App\\$modal" : i_class_exists("iApp", $modal);
}

function ipolicy($policy)
{
    return class_exists("\\App\\Policies\\$policy") ? "\\App\\Policies\\$policy" : i_class_exists("iApp\\Policies", $policy);
}

function iresource($resource)
{
    return class_exists("\\App\\Http\\Resources\\$resource") ? "\\App\\Http\\Resources\\$resource" : i_class_exists("iApp\\Http\\Resources", $resource);
}

function icontroller($controller)
{
    return class_exists("\\App\\Http\\Controllers\\$controller") ? "\\App\\Http\\Controllers\\$controller" : i_class_exists("iApp\\Http\\Controllers", $controller);
}

function iwebcontroller($controller)
{
    return icontroller("WEB\\Controllers\\$controller");
}

function iapicontroller($controller, $v = 1)
{
    return $v ? icontroller("API\\v$v\\$controller") : icontroller("API\\$controller");
}

function class_name($class_name, $plural = false, $lower = 0)
{
    $namespace = class_basename($class_name);
    $class_name = substr($namespace, -10, 10) == 'Controller' ? substr($namespace, 0, -10) : $namespace;
    $class_name = $plural ? Str::plural($class_name) : $class_name;
    switch ($lower) {
        case 1:
            return ucfirst($class_name);
        case 2:
            return strtolower($class_name);
        case 3:
            return strtoupper($class_name);
        case 4 :
            $class_name = preg_split('/(?=[A-Z])/', $class_name);
            unset($class_name[0]);
            $class_name = implode('.', $class_name);
            return strtolower($class_name);
        default:
            return $class_name;
    }
}

function getClosestKey($search, $arr)
{
    asort($arr);
    $keys = array_keys($arr);
    $values = array_values($arr);
    $prev = -1;
    if (array_search($search, $values) !== false)
        return $keys[array_search($search, $values)];
    else
        foreach ($values as $key => $item)
            if (($prev != -1) && ($search <= $item))
                return $keys[$search - $values[$prev] < $item - $search ? $prev : $key];
            else
                $prev = $key;
    return $keys[$prev == -1 ? 0 : $prev];
}

function _set_value($data, $path, $value)
{
    $temp = &$data;
    foreach (explode('.', $path) as $key) {
        $temp = &$temp[$key];
    }
    $temp = $value;
    unset($temp);
    return $data;
}

function _get_value($array, $parents,$default = null, $glue = '.')
{
    if (is_object($array))
        $array = (array) $array;
    if (!is_array($parents)) {
        $parents = explode($glue, $parents);
    }

    $ref = &$array;

    foreach ((array)$parents as $parent) {
        if (is_array($ref) && array_key_exists($parent, $ref)) {
            $ref = &$ref[$parent];
        } else {
            return $default;
        }
    }
    return $ref;
}

function _has_key(array $array, $parents, $glue = '.')
{
    if (!is_array($parents)) {
        $parents = explode($glue, $parents);
    }

    $ref = &$array;
    foreach ((array)$parents as $parent) {
        if (is_array($ref) && array_key_exists($parent, $ref)) {
            $ref = &$ref[$parent];
        } else {
            return false;
        }
    }
    return true;
}


function _unset_key(array $array, $parents, $glue = '.')
{
    if (!is_array($parents)) {
        $parents = explode($glue, $parents);
    }

    $ref = &$array;
    foreach ((array)$parents as $parent) {
        if (is_array($ref) && array_key_exists($parent, $ref)) {
            unset($ref[$parent]);
        } else {
            return false;
        }
    }
    return $ref;
}

function _save_child($kid, $items, $model, $set = [], $unset = [], $callback = null)
{
    $deletes = $kid->pluck('id')->toArray();
    $unseted = [];
    foreach ($items as $value) {
        foreach ($unset as $item){
            if (isset($value[$item])) $unseted[$item] = $value[$item];
            unset($value[$item]);
        }
        if (isset($value['id'])) {
            $deletes = array_diff($deletes, [$model::id($value['id'])]);
            $record = $model::findBySerial($value['id']);
            unset($value['id']);
            $record->update($value);
        } else
            $record = $kid->create(array_merge($value, $set));
        if (is_callable($callback))
            $callback($record, $unseted);
    }
    $model::destroy($deletes);
}

function extract_links($sourceURL) {
    $content=file_get_contents($sourceURL);
    $content = strip_tags($content,"<a>");
    $output_links = array();
    $subString = preg_split("/<\/a>/",$content);
    foreach ( $subString as $val ){
        if( strpos($val, "<a href=") !== FALSE ){
            $val = preg_replace("/.*<a\s+href=\"/sm","",$val);
            $val = preg_replace("/\".*/","",$val);
            array_push($output_links, $sourceURL.$val);
        }
    }
    array_shift($output_links);
    return $output_links;
}
