<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/1/21, 5:23 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Methods;

use DateTime;

trait Data
{
    protected $dataTypes = ['boolean', 'integer', 'double', 'float', 'string', 'NULL'];

    public function getValueByType($value, $type = null) {
        $type = $type ? : gettype($value);
        if (is_json($value)) $type = 'array';
        switch ($type) {
            case 'array':
                return json_decode($value, true);
            case 'object':
                return json_decode($value);
            case 'datetime':
                return $this->asDateTime($value);
            case 'model': {
                if (strpos($value, '#') === false) {
                    return new $value();
                }
                list($class, $id) = explode('#', $value);
                return with(new $class())->findOrFail($id);
            }
        }

        if (in_array($type, $this->dataTypes)) {
            settype($value, $type);
        }

        return $value;
    }

    public function renderGetValue($value, $name = 'value') {
        $name_type = $name === 'value' ? 'type' : "${name}_type";
        $type = $this->$name_type ?: 'null';
        return $this->getValueByType($value, $type);
    }

    public function renderSetValue($value, $name = 'value') {
        $name_type = $name === 'value' ? 'type' : "${name}_type";
        $type = gettype($value);
        if ($value instanceof DateTime) {
            $this->$name_type = 'datetime';
            $this->attributes[$name] = $this->fromDateTime($value);
        } elseif ($value instanceof Model) {
            $this->$name_type = 'model';
            $this->attributes[$name] = get_class($value).(!$value->exists ? '' : '#'.$value->getKey());
        } elseif (is_object($value)) {
            $this->$name_type = 'object';
            $this->attributes[$name] = json_encode($value);
        } elseif (is_array($value)) {
            $this->$name_type = 'array';
            $this->attributes[$name] = json_encode($value);
        } else {
            $this->$name_type = in_array($type, $this->dataTypes) ? $type : 'string';
            $this->attributes[$name] = $value;
        }
    }
}
