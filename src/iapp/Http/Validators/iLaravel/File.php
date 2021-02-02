<?php


namespace iLaravel\Core\iApp\Http\Validators\iLaravel;


trait File
{
    public function validateIMimes($attribute, $value, $parameters)
    {
        if ($this->validateMimes($attribute, $value, $parameters)) return true;
        return in_array($value->getClientOriginalExtension(), $parameters);
    }
}
