<?php

use Illuminate\Support\Str;

function i_class_exists($patch, $class){
    foreach (iconfig('plugins') as $plugin) {
        if (class_exists("\\iLaravel\\$plugin\\$patch\\$class"))
            return "\\iLaravel\\$plugin\\$patch\\$class";
    }
    return false;
}

function imodal($modal){
    return i_class_exists("iApp", $modal) ?  : "\\App\\$modal";
}

function iresource($resource){
    return i_class_exists("iApp\\Http\\Resources", $resource) ?  : "\\App\\Http\\Resources\\$resource";
}

function icontroller($controller){
    return i_class_exists("iApp\\Http\\Controllers", $controller) ? : "\\App\\Http\\Controllers\\$controller";
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
