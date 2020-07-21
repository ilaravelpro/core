<?php

use Illuminate\Support\Str;

function imodal($modal){
    return class_exists("\\App\\$modal") ? "\\App\\$modal" : "\\iLaravel\\Core\\IApp\\$modal";
}

function iresource($resource){
    return class_exists("\\App\\Http\\Resources\\$resource") ? "\\App\\Http\\Resources\\$resource" : "\\iLaravel\\Core\\IApp\\Http\\Resources\\$resource";
}

function icontroller($controller){
    return class_exists("\\App\\Http\\Controllers\\$controller") ? "\\App\\Http\\Controllers\\$controller" : "\\iLaravel\\Core\\IApp\\Http\\Controllers\\$controller";
}

function iwebcontroller($controller){
    return icontroller("WEB\\Controllers\\$controller");
}

function iapicontroller($controller, $v = 1){
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
