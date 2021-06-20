<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 5:22 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Validators\iLaravel;


trait Phone
{
    public function validateMobile($attribute, $value, $parameters, $validator)
    {
        return \iLaravel\Core\Vendor\Validations\iPhone::parse($value, $parameters);
    }

    public function validatePhone($attribute, $value, $parameters, $validator)
    {
        return \iLaravel\Core\Vendor\Validations\iPhone::parse($value, $parameters);
    }
}
