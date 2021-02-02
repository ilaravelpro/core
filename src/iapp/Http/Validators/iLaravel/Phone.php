<?php


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
