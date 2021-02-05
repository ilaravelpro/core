<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 4:01 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Validators\iLaravel;


trait Numeric
{
    public function validateDouble($attribute, $value, $parameters, $validator)
    {
        $min = isset($parameters[0]) ? $parameters[0] : 1;
        $max = isset($parameters[1]) ? $parameters[1] : 2;
        $parameters = ['/^\d*(\.\d{'.$min.','.$max.'})?$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }

    public function validateLongitude($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }

    public function validateLatitude($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }
}
