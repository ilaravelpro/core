<?php

namespace iLaravel\Core\iApp\Http\Validators;

use Illuminate\Validation\Validator;

class iLaravel extends Validator
{
    public function validateSerial($attribute, $value, $parameters, $validator)
    {
        if(!is_null($value))
            return is_integer($value);
        return true;
    }
    public function validateMobile($attribute, $value, $parameters, $validator)
    {
        list($mobile, $country, $code) = \iLaravel\Core\Vendor\iMobile::parse($value, $parameters);
        if(!$mobile)
        {
            return false;
        }
        return true;
    }

    public function validateOneOf($attribute, $value, $parameters, $validator)
    {
        array_push($parameters, $attribute);
        $one_of = false;
        foreach ($parameters as $key => $value) {
            if(isset($this->data[$value]) && $this->data[$value])
            {
                $one_of = true;
                break;
            }
        }
        return $one_of;
    }

    public function validateDouble($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^\d*(\.\d{1,2})?$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }
}
