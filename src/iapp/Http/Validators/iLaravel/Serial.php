<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 3:59 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Validators\iLaravel;

trait Serial
{
    public function validateSerial($attribute, $value, $parameters, $validator)
    {
        if (!isset($parameters[0])) return true;
        $model = imodal(ucfirst($parameters[0]));
        if (is_array($value)) {
            $value = array_unique($value);
            foreach ($value as $dk => $dv) {
                $value[$dk] = $model::encode_id($dv);
            }
        } else {
            $value = $model::encode_id($value);
        }
        return $this->checkSerial($attribute, $value, $parameters, $validator);
    }

    public function validateExistsSerial($attribute, $value, $parameters, $validator)
    {
        if (!isset($parameters[0])) return true;
        $model = imodal(ucfirst($parameters[0]));
        if (is_array($value)) {
            $value = array_unique($value);
            foreach ($value as $dk => $dv) {
                $value[$dk] = $model::encode_id($dv);
            }
        } else {
            $value = $model::encode_id($value);
        }
        return $this->checkSerial($attribute, $value, $parameters, $validator);
    }

    public function checkSerial($attribute, $value, $parameters, $validator)
    {
        if (is_array($value)) {
            $all_numeric = true;
            foreach ($value as $key) {
                if (!(is_numeric($key))) {
                    $all_numeric = false;
                    break;
                }
            }
            return $all_numeric;
        }
        if (!is_null($value))
            return is_integer($value);
        return true;
    }
}
