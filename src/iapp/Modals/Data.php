<?php


namespace iLaravel\Core\iApp\Modals;

use DateTime;

trait Data
{
    protected $dataTypes = ['boolean', 'integer', 'double', 'float', 'string', 'NULL'];

    public function renderGetValue($value, $name_type = 'type') {
        $type = $this->$name_type ?: 'null';

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

    public function renderSetValue($value, $name_type = 'type') {
        $type = gettype($value);

        if (is_array($value)) {
            $this->$name_type = 'array';
            $this->attributes['value'] = json_encode($value);
        } elseif ($value instanceof DateTime) {
            $this->$name_type = 'datetime';
            $this->attributes['value'] = $this->fromDateTime($value);
        } elseif ($value instanceof Model) {
            $this->$name_type = 'model';
            $this->attributes['value'] = get_class($value).(!$value->exists ? '' : '#'.$value->getKey());
        } elseif (is_object($value)) {
            $this->$name_type = 'object';
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->$name_type = in_array($type, $this->dataTypes) ? $type : 'string';
            $this->attributes['value'] = $value;
        }
    }
}
