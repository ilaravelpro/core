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

    public function validateIp($attribute, $value, $parameters, $validator)
    {
        if (in_array(['6', 'v6'], $parameters))
            $parameters = ['/(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))/'];
        else
            $parameters = ['/\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }
}
