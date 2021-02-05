<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 6:40 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Validators;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class iLaravel extends Validator
{
    use iLaravel\Serial,
        iLaravel\Phone,
        iLaravel\Numeric,
        iLaravel\File;

    public function validateOneOf($attribute, $value, $parameters, $validator)
    {
        array_push($parameters, $attribute);
        $one_of = false;
        foreach ($parameters as $key => $value) {
            if (isset($this->data[$value]) && $this->data[$value]) {
                $one_of = true;
                break;
            }
        }
        return $one_of;
    }

    public function validateCountry($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^[a-zA-Z]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }

    public function validateIEmail($attribute, $value, $parameters, $validator)
    {
        return \iLaravel\Core\Vendor\Validations\iEmail::parse($value, $parameters);
    }

    public function validateUsername($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^[a-z0-9_-]{3,16}$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }

    public function validateWebsite($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }

    public function validatePassword($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^[a-zA-Z0-9._\-!?@#$%&*\/]*$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }
}
