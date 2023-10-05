<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 8:13 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

use Illuminate\Http\UploadedFile;
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
            $args = func_get_args();
            $class_name = preg_split('/(?=[A-Z])/', $class_name);
            unset($class_name[0]);
            $class_name = implode(isset($args[3]) && $args[3] ? $args[3] : '.', $class_name);
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

function _set_value($data, $path, $value, $checkNumber = true)
{
    $temp = &$data;
    $keys = explode('.', $path);
    foreach ($keys as $index => $key) {
        if (is_numeric($key) && $checkNumber) {
            $temp = (object) $temp;
            $temp = &$temp->$key;
        }else
            $temp = &$temp[$key];
    }
    $temp = $value;
    unset($temp);
    return (array) $data;
}

function _get_value($array, $parents,$default = null, $glue = '.', $prepend = null, $append = null)
{
    if (is_object($array))
        $array = (array) $array;
    if (!is_array($parents)) {
        $parents = explode($glue, $parents);
    }

    $ref = &$array;

    foreach ((array)$parents as $parent) {
        if (is_object($ref)) $ref = (array) $ref;
        if (is_array($ref) && array_key_exists($parent, $ref)) {
            $ref = &$ref[$parent];
        } else {
            return $default;
        }
    }
    if ($prepend)
        $ref = $prepend . $ref;
    if ($append)
        $ref .= $append;
    return $ref;
}

function _has_key(array $array, $parents, $glue = '.')
{
    if (!is_array($parents)) {
        $parents = explode($glue, $parents);
    }

    $ref = &$array;
    foreach ((array)$parents as $parent) {
        if (is_object($ref)) $ref = (array) $ref;
        if (is_array($ref) && array_key_exists($parent, $ref)) {
            $ref = &$ref[$parent];
        } else {
            return false;
        }
    }
    return true;
}


function _unset_key($array, $parents, $glue = '.')
{
    if (!is_array($parents)) {
        $parents = explode($glue, $parents);
    }

    $prevEl = null;
    $ref = &$array;
    try {
        foreach ($parents as &$parent) {
            $prevEl = &$ref;
            $ref = &$ref[$parent];
        }
    }catch (\Throwable $exception) {}
    try {
        if ($prevEl !== null)
            unset($prevEl[$parent]);
    }catch (\Throwable $exception) {}
    return $array;
}

function _save_child($kid, $items, $model, $set = [], $unset = [], $callback = null)
{
    $deletes = $kid->pluck('id')->toArray();
    $unseted = [];
    foreach ($items as $index => $value) {
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
        $items[$index] = $record;
    }
    $model::destroy($deletes);
    return [$items, $deletes];
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

function _get_user_ip(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function domain_exists($email, $record = 'MX'){
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, $record);
}
