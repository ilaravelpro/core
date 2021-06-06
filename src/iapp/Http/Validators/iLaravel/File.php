<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 4:01 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Validators\iLaravel;


trait File
{
    public function validateIMimes($attribute, $value, $parameters)
    {
        if ($this->validateMimes($attribute, $value, $parameters)) return true;
        return in_array($value->getClientOriginalExtension(), $parameters);
    }

    public function validateDataUrl($attribute, $value, $parameters)
    {
        $regex = "/^\s*data:(?<media_type>(?<mime_type>[a-z\-]+\/[a-z\-\+]+)(?<params>(;[a-z\-]+\=[a-z\-]+)*))?(?<encoding>;base64)?,(?<data>[a-z0-9\!\$\&\'\,\(\)\*\+\,\;\=\-\.\_\~\:\@\/\?\%\s]*\s*)$/i";
        return $this->validateRegex($attribute, $value, [$regex]);
    }
}
